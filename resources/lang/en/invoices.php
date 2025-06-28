<?php

return [
    // Main
    'title' => 'Invoices',
    'invoices' => 'Invoices',
    'invoice' => 'Invoice',
    'new_invoice' => 'New Invoice',
    'add_invoice' => 'Add Invoice',
    'create_invoice' => 'Create Invoice',
    'edit_invoice' => 'Edit Invoice',
    'view_invoice' => 'View Invoice',
    'delete_invoice' => 'Delete Invoice',
    'invoice_details' => 'Invoice Details',
    'invoice_information' => 'Invoice Information',
    'all_invoices' => 'All Invoices',

    // Invoice Fields
    'invoice_number' => 'Invoice Number',
    'invoice_date' => 'Invoice Date',
    'due_date' => 'Due Date',
    'customer' => 'Customer',
    'status' => 'Status',
    'subtotal' => 'Subtotal',
    'tax_amount' => 'Tax Amount',
    'discount_amount' => 'Discount Amount',
    'total_amount' => 'Total Amount',
    'paid_amount' => 'Paid Amount',
    'remaining_amount' => 'Remaining Amount',
    'notes' => 'Notes',
    'items' => 'Items',
    'quantity' => 'Quantity',
    'unit_price' => 'Unit Price',
    'total_price' => 'Total Price',

    // Invoice Status
    'draft' => 'Draft',
    'pending' => 'Pending',
    'sent' => 'Sent',
    'paid' => 'Paid',
    'partially_paid' => 'Partially Paid',
    'overdue' => 'Overdue',
    'cancelled' => 'Cancelled',

    // Actions
    'mark_as_paid' => 'Mark as Paid',
    'send_reminder' => 'Send Reminder',
    'print_invoice' => 'Print Invoice',
    'download_pdf' => 'Download PDF',

    // Messages
    'invoice_created' => 'Invoice created successfully',
    'invoice_updated' => 'Invoice updated successfully',
    'invoice_deleted' => 'Invoice deleted successfully',
    'invoice_marked_paid' => 'Invoice marked as paid successfully',
    'reminder_sent' => 'Reminder sent successfully',
    'no_invoices_found' => 'No invoices found',
    'invoice_not_found' => 'Invoice not found',

    // Statistics
    'total_invoices' => 'Total Invoices',
    'pending_invoices' => 'Pending Invoices',
    'paid_invoices' => 'Paid Invoices',
    'overdue_invoices' => 'Overdue Invoices',
    'total_revenue' => 'Total Revenue',
    'outstanding_amount' => 'Outstanding Amount',

    // Validation
    'validation' => [
        'customer_required' => 'Customer is required',
        'invoice_date_required' => 'Invoice date is required',
        'due_date_required' => 'Due date is required',
        'items_required' => 'Items are required',
        'quantity_positive' => 'Quantity must be positive',
        'price_positive' => 'Price must be positive',
    ],
];
