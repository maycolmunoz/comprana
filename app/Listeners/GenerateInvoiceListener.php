<?php

namespace App\Listeners;

use App\Events\CreateOrderEvent;
use App\Models\Order;
use App\Models\Product;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceListener
{
    private const PAGE_WIDTH = 80.0;

    private const PAGE_HEIGHT = 258.0;

    private const MARGIN = 5.0;

    private const BOTTOM_MARGIN = 12.0;

    private const LINE_HEIGHT = 4.0;

    private const QTY_WIDTH = 7.0;

    private const DESCRIPTION_WIDTH = 33.0;

    private const UNIT_PRICE_WIDTH = 15.0;

    private const LINE_TOTAL_WIDTH = 15.0;

    private const TABLE_FONT_SIZE = 7.0;

    private Fpdf $pdf;

    /**
     * Handle the event.
     */
    public function handle(CreateOrderEvent $event): void
    {
        $order = $event->order;

        if (filled($order->invoice)) {
            return;
        }

        $invoiceName = $order->invoiceFileName();

        $this->pdf = new Fpdf('P', 'mm', [self::PAGE_WIDTH, self::PAGE_HEIGHT]);
        $this->pdf->SetTitle('Factura #'.$order->id);
        $this->pdf->SetAuthor('Comprana');
        $this->pdf->SetCreator('Comprana');
        $this->pdf->SetMargins(self::MARGIN, 6, self::MARGIN);
        $this->pdf->SetAutoPageBreak(false);

        $this->firstPage($order);

        $total = $this->drawItems($event->products, $order);

        $this->drawTotals($order, $order->total ?? $total);

        Storage::disk('invoices')->put($invoiceName, $this->pdf->Output('S'));

        $order->update(['invoice' => $invoiceName]);

        $this->decrementStock($event->products);
    }

    private function firstPage(Order $order): void
    {
        $this->addPage();
        $this->drawCompanyHeader();
        $this->rule(2);
        $this->drawInvoiceMeta($order);
        $this->rule(2);
        $this->drawCustomer($order);
        $this->rule(2);
        $this->drawItemsHeader();
    }

    private function continuationPage(Order $order, bool $withItemsHeader = true): void
    {
        $this->addPage();
        $this->drawCompanyHeader();

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(0, 5, $this->text('Factura #'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT).' · continuación'), 0, 1, 'C');

        $this->rule(2);

        if ($withItemsHeader) {
            $this->drawItemsHeader();
        }
    }

    private function addPage(): void
    {
        $this->pdf->AddPage(size: [self::PAGE_WIDTH, self::PAGE_HEIGHT]);
        $this->pdf->SetMargins(self::MARGIN, 6, self::MARGIN);
    }

    private function drawCompanyHeader(): void
    {
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(0, 6, $this->text(strtoupper(config('app.name') ?: 'Comprana')), 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 7.5);
        $this->pdf->SetTextColor(110, 110, 110);
        $this->pdf->Cell(0, 4, $this->text('COMPROBANTE DE COMPRA'), 0, 1, 'C');
        $this->pdf->SetTextColor(0, 0, 0);
    }

    private function drawInvoiceMeta(Order $order): void
    {
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(35, 5, $this->text('FACTURA'), 0, 0, 'L');
        $this->pdf->Cell(35, 5, $this->text('#'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT)), 0, 1, 'R');

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(0, 4, $this->text('Fecha: '.($order->created_at ?? now())->format('d/m/Y H:i')), 0, 1, 'L');

        $payment = 'Estado: '.$order->paymentStatusLabel();

        if (filled($order->payment_id)) {
            $payment .= ' · MP #'.$order->payment_id;
        }

        $this->multiCell(0, 4, $payment);
    }

    private function drawCustomer(Order $order): void
    {
        $this->sectionTitle('CLIENTE');

        $this->pdf->SetFont('Arial', '', 8);
        $this->multiCell(0, 4, $order->user?->name ?? 'Cliente');
        $this->multiCell(0, 4, 'Tel. '.$order->phone);
        $this->multiCell(0, 4, 'Dirección: '.$order->address);
    }

    private function sectionTitle(string $title): void
    {
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->SetTextColor(120, 120, 120);
        $this->pdf->Cell(0, 4, $this->text($title), 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
    }

    private function drawItemsHeader(): void
    {
        $this->pdf->SetFont('Arial', 'B', 6.5);
        $this->pdf->SetTextColor(120, 120, 120);

        $this->pdf->Cell(self::QTY_WIDTH, 4, $this->text('CANT'), 0, 0, 'C');
        $this->pdf->Cell(self::DESCRIPTION_WIDTH, 4, $this->text('DESCRIPCIÓN'), 0, 0, 'L');
        $this->pdf->Cell(self::UNIT_PRICE_WIDTH, 4, $this->text('P. UNIT'), 0, 0, 'R');
        $this->pdf->Cell(self::LINE_TOTAL_WIDTH, 4, $this->text('TOTAL'), 0, 1, 'R');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->rule(1);
    }

    /**
     * @param  array<int, array{id?: int|string, title?: string, quantity?: int|string, unit_price?: float|int|string}>  $products
     */
    private function drawItems(array $products, Order $order): float
    {
        $total = 0.0;

        foreach ($products as $product) {
            $quantity = (int) ($product['quantity'] ?? 0);
            $unitPrice = (float) ($product['unit_price'] ?? 0);
            $title = (string) ($product['title'] ?? 'Producto');

            $total += $unitPrice * $quantity;

            $this->pdf->SetFont('Arial', '', self::TABLE_FONT_SIZE);
            $rowHeight = $this->lineCount($title, self::DESCRIPTION_WIDTH) * self::LINE_HEIGHT;

            if ($this->pdf->GetY() + $rowHeight + 1.5 > self::PAGE_HEIGHT - self::BOTTOM_MARGIN) {
                $this->continuationPage($order);
            }

            $this->drawItem($title, $unitPrice, $quantity, $rowHeight);
        }

        return $total;
    }

    private function drawItem(string $title, float $unitPrice, int $quantity, float $rowHeight): void
    {
        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        $this->pdf->SetFont('Arial', '', self::TABLE_FONT_SIZE);

        $this->pdf->Cell(self::QTY_WIDTH, self::LINE_HEIGHT, $this->text((string) $quantity), 0, 0, 'C');
        $this->pdf->MultiCell(
            self::DESCRIPTION_WIDTH,
            self::LINE_HEIGHT,
            $this->wrapLongWords($this->text($title), self::DESCRIPTION_WIDTH),
            0,
            'L',
            false
        );

        $descriptionBottom = $this->pdf->GetY();

        $this->pdf->SetXY($x + self::QTY_WIDTH + self::DESCRIPTION_WIDTH, $y);
        $this->amountCell(self::UNIT_PRICE_WIDTH, self::LINE_HEIGHT, $this->money($unitPrice), self::TABLE_FONT_SIZE);
        $this->amountCell(self::LINE_TOTAL_WIDTH, self::LINE_HEIGHT, $this->money($unitPrice * $quantity), self::TABLE_FONT_SIZE);

        $this->pdf->SetXY(self::MARGIN, max($descriptionBottom, $y + $rowHeight));
        $this->pdf->Ln(1.5);
    }

    private function drawTotals(Order $order, float|int $total): void
    {
        if ($this->pdf->GetY() + 30 > self::PAGE_HEIGHT - self::BOTTOM_MARGIN) {
            $this->continuationPage($order, false);
        }

        $this->rule(2);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(40, 6, $this->text('TOTAL PAGADO'), 0, 0, 'L');
        $this->amountCell(30, 6, $this->money($total), 9);
        $this->pdf->Ln(6);

        $this->pdf->Ln(4);
        $this->rule(2);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(0, 5, $this->text('Gracias por su compra'), 0, 1, 'C');
    }

    private function amountCell(float $width, float $height, string $text, float $fontSize): void
    {
        $size = $fontSize;

        while ($size > 5.5 && $this->pdf->GetStringWidth($text) > $width - 1) {
            $size -= 0.5;
            $this->pdf->SetFontSize($size);
        }

        $this->pdf->Cell($width, $height, $text, 0, 0, 'R');
        $this->pdf->SetFont('Arial', '', $fontSize);
    }

    private function rule(float $spacing = 2): void
    {
        $this->pdf->SetDrawColor(210, 210, 210);
        $this->pdf->SetLineWidth(0.2);
        $y = $this->pdf->GetY();
        $this->pdf->Line(self::MARGIN, $y, self::PAGE_WIDTH - self::MARGIN, $y);
        $this->pdf->Ln($spacing);
    }

    private function multiCell(float $width, float $height, string $text, string $align = 'L'): void
    {
        $width = $width > 0 ? $width : self::PAGE_WIDTH - 2 * self::MARGIN;

        $this->pdf->MultiCell($width, $height, $this->wrapLongWords($this->text($text), $width), 0, $align, false);
    }

    private function lineCount(string $text, float $width): int
    {
        $maxWidth = max(1, $width - 2);
        $wrapped = $this->wrapLongWords($this->text($text), $width);
        $lines = 0;

        foreach (explode("\n", $wrapped) as $paragraph) {
            $lines++;
            $currentLine = '';

            foreach (preg_split('/\s+/', trim($paragraph)) ?: [] as $word) {
                $candidate = $currentLine === '' ? $word : $currentLine.' '.$word;

                if ($this->pdf->GetStringWidth($candidate) <= $maxWidth) {
                    $currentLine = $candidate;
                } else {
                    $lines++;
                    $currentLine = $word;
                }
            }
        }

        return max(1, $lines);
    }

    private function wrapLongWords(string $text, float $width): string
    {
        $maxWidth = max(1, $width - 2);
        $words = preg_split('/\s+/', trim($text)) ?: [];
        $wrapped = [];

        foreach ($words as $word) {
            while ($this->pdf->GetStringWidth($word) > $maxWidth) {
                $chunk = '';

                for ($i = 0, $length = strlen($word); $i < $length; $i++) {
                    if ($this->pdf->GetStringWidth($chunk.$word[$i]) > $maxWidth && $chunk !== '') {
                        break;
                    }

                    $chunk .= $word[$i];
                }

                if ($chunk === '') {
                    $chunk = $word[0];
                }

                $wrapped[] = $chunk;
                $word = substr($word, strlen($chunk));
            }

            if ($word !== '') {
                $wrapped[] = $word;
            }
        }

        return implode(' ', $wrapped);
    }

    private function money(float|int|string|null $value): string
    {
        return '$'.number_format((int) $value, 0, ',', '.');
    }

    private function text(?string $value): string
    {
        $value = (string) $value;
        $encoded = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);

        return $encoded === false ? preg_replace('/[^\x20-\x7E]/', '?', $value) : $encoded;
    }

    /**
     * @param  array<int, array{id?: int|string, title?: string, quantity?: int|string}>  $products
     */
    private function decrementStock(array $products): void
    {
        foreach ($products as $product) {
            $model = Product::find($product['id'] ?? null)
                ?? Product::firstWhere('name', $product['title'] ?? null);

            if (! $model) {
                continue;
            }

            $model->decrement('stock', min((int) ($product['quantity'] ?? 0), $model->stock));
        }
    }
}
