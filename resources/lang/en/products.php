<?php

return [
    // Main
    'title' => 'Products',
    'products' => 'Products',
    'product' => 'Product',
    'new_product' => 'New Product',
    'add_product' => 'Add Product',
    'create_product' => 'Create Product',
    'edit_product' => 'Edit Product',
    'view_product' => 'View Product',
    'delete_product' => 'Delete Product',
    'product_details' => 'Product Details',
    'product_information' => 'Product Information',
    'all_products' => 'All Products',

    // Product Fields
    'product_code' => 'Product Code',
    'product_name' => 'Product Name',
    'generic_name' => 'Generic Name',
    'brand_name' => 'Brand Name',
    'description' => 'Description',
    'category' => 'Category',
    'manufacturer' => 'Manufacturer',
    'supplier' => 'Supplier',
    'unit' => 'Unit',
    'price' => 'Price',
    'cost' => 'Cost',
    'barcode' => 'Barcode',
    'sku' => 'SKU',
    'weight' => 'Weight',
    'dimensions' => 'Dimensions',
    'status' => 'Status',
    'notes' => 'Notes',

    // Pharmaceutical Fields
    'dosage_form' => 'Dosage Form',
    'strength' => 'Strength',
    'pack_size' => 'Pack Size',
    'composition' => 'Composition',
    'indications' => 'Indications',
    'contraindications' => 'Contraindications',
    'side_effects' => 'Side Effects',
    'dosage_instructions' => 'Dosage Instructions',
    'storage_conditions' => 'Storage Conditions',
    'prescription_status' => 'Prescription Status',
    'atc_code' => 'ATC Code',

    // Product Types
    'medicine' => 'Medicine',
    'medical_device' => 'Medical Device',
    'supplement' => 'Supplement',
    'cosmetic' => 'Cosmetic',
    'veterinary' => 'Veterinary',

    // Dosage Forms
    'tablet' => 'Tablet',
    'capsule' => 'Capsule',
    'syrup' => 'Syrup',
    'injection' => 'Injection',
    'cream' => 'Cream',
    'ointment' => 'Ointment',
    'drops' => 'Drops',
    'inhaler' => 'Inhaler',
    'spray' => 'Spray',

    // Prescription Status
    'prescription' => 'Prescription',
    'otc' => 'Over-the-Counter',
    'controlled' => 'Controlled',

    // Status
    'active' => 'Active',
    'inactive' => 'Inactive',
    'discontinued' => 'Discontinued',

    // Messages
    'product_created' => 'Product created successfully',
    'product_updated' => 'Product updated successfully',
    'product_deleted' => 'Product deleted successfully',
    'no_products_found' => 'No products found',
    'product_not_found' => 'Product not found',

    // Import/Export
    'import_products' => 'Import Products',
    'export_products' => 'Export Products',
    'download_template' => 'Download Template',
    'upload_file' => 'Upload File',

    // Validation
    'validation' => [
        'product_code_required' => 'Product code is required',
        'product_code_unique' => 'Product code already exists',
        'product_name_required' => 'Product name is required',
        'category_required' => 'Category is required',
        'price_required' => 'Price is required',
        'price_positive' => 'Price must be positive',
        'cost_positive' => 'Cost must be positive',
    ],
];
