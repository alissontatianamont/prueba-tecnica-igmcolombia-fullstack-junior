<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $invoice->inv_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; color: #2563eb; margin-bottom: 5px; }
        .invoice-title { font-size: 20px; font-weight: bold; margin-top: 10px; }
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .invoice-details, .client-details { width: 48%; }
        .section-title { font-weight: bold; font-size: 14px; margin-bottom: 10px; color: #4b5563; }
        .detail-row { margin-bottom: 5px; font-size: 12px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        .items-table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .totals { margin-top: 20px; }
        .totals-table { width: 40%; margin-left: auto; }
        .totals-table td { padding: 5px 10px; border: none; font-size: 12px; }
        .total-final { font-weight: bold; font-size: 14px; border-top: 2px solid #333; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">IGM COLOMBIA</div>
        <div class="invoice-title">FACTURA</div>
    </div>

    <div class="invoice-info">
        <div class="invoice-details">
            <div class="section-title">DETALLES DE LA FACTURA</div>
            <div class="detail-row"><strong>Número:</strong> {{ $invoice->inv_number }}</div>
            <div class="detail-row"><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($invoice->inv_issue_date)->format('d/m/Y') }}</div>
            <div class="detail-row"><strong>Fecha de Vencimiento:</strong> {{ \Carbon\Carbon::parse($invoice->inv_due_date)->format('d/m/Y') }}</div>
            <div class="detail-row"><strong>Estado:</strong> {{ ucfirst($invoice->inv_status) }}</div>
            <div class="detail-row"><strong>Vendedor:</strong> {{ $invoice->user->name }}</div>
        </div>
        
        <div class="client-details">
            <div class="section-title">DATOS DEL CLIENTE</div>
            <div class="detail-row"><strong>Nombre:</strong> {{ $invoice->client->cli_first_name }} {{ $invoice->client->cli_middle_name }} {{ $invoice->client->cli_last_name }} {{ $invoice->client->cli_second_last_name }}</div>
            <div class="detail-row"><strong>{{ ucfirst($invoice->client->cli_document_type) }}:</strong> {{ $invoice->client->cli_document_number }}</div>
            <div class="detail-row"><strong>Email:</strong> {{ $invoice->client->cli_email }}</div>
            <div class="detail-row"><strong>Teléfono:</strong> {{ $invoice->client->cli_phone }}</div>
            <div class="detail-row"><strong>Dirección:</strong> {{ $invoice->client->cli_address }}</div>
        </div>
    </div>

    @if($invoice->inv_description)
    <div style="margin-bottom: 20px;">
        <div class="section-title">DESCRIPCIÓN</div>
        <div style="font-size: 12px;">{{ $invoice->inv_description }}</div>
    </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Producto</th>
                <th style="width: 15%;" class="text-right">Cantidad</th>
                <th style="width: 15%;" class="text-right">Precio Unit.</th>
                <th style="width: 15%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->invoiceItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->pro_name }}</strong>
                    @if($item->product->pro_description)
                    <br><small style="color: #6b7280;">{{ $item->product->pro_description }}</small>
                    @endif
                </td>
                <td class="text-right">{{ $item->ii_quantity }}</td>
                <td class="text-right">${{ number_format($item->ii_unit_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->ii_total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">${{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA ({{ $invoice->inv_iva_percentage }}%):</strong></td>
                <td class="text-right">${{ number_format($ivaAmount, 2) }}</td>
            </tr>
            <tr class="total-final">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>${{ number_format($total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    @if($invoice->inv_notes)
    <div style="margin-top: 30px;">
        <div class="section-title">NOTAS</div>
        <div style="font-size: 11px; line-height: 1.4;">{{ $invoice->inv_notes }}</div>
    </div>
    @endif

    <div class="footer">
        <p>Generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>IGM Colombia - Sistema de Facturación</p>
    </div>
</body>
</html>