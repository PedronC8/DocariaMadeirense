@extends('layouts.app')

@section('title', 'Editar Encomenda - A Docaria')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .product-card.selected {
        border-color: #3b7ddd;
        background-color: #f8f9fa;
    }
    .product-img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .quantity-controls button {
        width: 28px;
        height: 28px;
        padding: 0;
        font-size: 1rem;
        line-height: 1;
    }
    .quantity-controls input {
        width: 50px;
        text-align: center;
        font-size: 0.875rem;
    }
    .category-tabs .nav-link {
        cursor: pointer;
    }
    .subcategory-tabs {
        margin-top: 0.5rem;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }
    .subcategory-tabs .btn {
        margin: 0.2rem;
    }
    .summary-card {
        position: sticky;
        top: 20px;
    }
    .order-item-row {
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 0;
    }
    .order-item-row:last-child {
        border-bottom: none;
    }
    .product-card .card-body {
        padding: 0.75rem;
    }
    .product-card .card-title {
        font-size: 0.9rem;
    }
    .product-card .text-muted {
        font-size: 0.75rem;
    }
    .product-card .text-primary {
        font-size: 0.9rem;
    }
    /* Esconder setas dos inputs number */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .invoice-number-input {
        opacity: 0.5;
        pointer-events: none;
    }
    .invoice-number-input.enabled {
        opacity: 1;
        pointer-events: auto;
    }
    /* Corrigir altura do Select2 */
    .select2-container--bootstrap-5 .select2-selection--single {
        min-height: 33px !important;
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Editar Encomenda #{{ $order->id }}</strong></h3>
    </div>
    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('orders.show', $order) }}" class="btn btn-info me-2">
            <i class="align-middle" data-feather="eye"></i> Ver Detalhes
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="align-middle" data-feather="arrow-left"></i> Voltar
        </a>
    </div>
</div>

<form id="orderForm" method="POST" action="{{ route('orders.update', $order) }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Área Principal -->
        <div class="col-lg-8">
            <!-- Card: Cliente -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Selecione um cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            data-phone="{{ $client->contact }}"
                                            data-address="{{ $client->address }}"
                                            {{ ($order->client_id == $client->id || old('client_id') == $client->id) ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Telefone</label>
                            <input type="text" id="client_phone" class="form-control" readonly 
                                   value="{{ $order->client->contact ?? '---' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Morada</label>
                            <input type="text" id="client_address" class="form-control" readonly 
                                   value="{{ $order->client->address ?? '---' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-flex align-items-center">
                                <input type="checkbox" id="hasInvoice" class="form-check-input me-2" 
                                       {{ $order->invoice ? 'checked' : '' }}>
                                Nº Fatura
                            </label>
                            <input type="text" name="invoice" id="invoiceNumber" 
                                   class="form-control invoice-number-input {{ $order->invoice ? 'enabled' : '' }}" 
                                   placeholder="Ex: 2026/001"
                                   value="{{ old('invoice', $order->invoice) }}"
                                   {{ !$order->invoice ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Datas (4 campos visíveis) -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Data Encomenda <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="orderDate" 
                                   class="form-control datepicker" 
                                   value="{{ $order->order_date->format('d/m/Y') }}"
                                   autocomplete="off" 
                                   required>
                            <input type="hidden" name="order_date" id="orderDateHidden" value="{{ $order->order_date->format('Y-m-d') }}">
                            @error('order_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pronto Em <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="readyDate" 
                                   class="form-control datepicker" 
                                   value="{{ $order->ready_date->format('d/m/Y') }}"
                                   autocomplete="off" 
                                   required>
                            <input type="hidden" name="ready_date" id="readyDateHidden" value="{{ $order->ready_date->format('Y-m-d') }}">
                            @error('ready_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Entrega <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="deliveryDate" 
                                   class="form-control datepicker" 
                                   value="{{ $order->delivery_date->format('d/m/Y') }}"
                                   autocomplete="off" 
                                   required>
                            <input type="hidden" name="delivery_date" id="deliveryDateHidden" value="{{ $order->delivery_date->format('Y-m-d') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Desejada</label>
                            <input type="text" 
                                   id="desiredDate" 
                                   class="form-control datepicker" 
                                   value="{{ $order->desired_date ? $order->desired_date->format('d/m/Y') : '' }}"
                                   autocomplete="off">
                            <input type="hidden" name="desired_date" id="desiredDateHidden" value="{{ $order->desired_date ? $order->desired_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Produtos -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Produtos</h5>
                </div>
                <div class="card-body">
                    <!-- Tabs de Categorias -->
                    <ul class="nav nav-tabs category-tabs mb-2" role="tablist" id="categoryTabs">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-category="all" type="button">Todos</button>
                        </li>
                        @php
                            $categories = $products->pluck('subcategory.category')->unique('id')->filter();
                        @endphp
                        @foreach($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-category="{{ $category->id }}" type="button">
                                    {{ $category->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Subcategorias -->
                    <div class="subcategory-tabs" id="subcategoryContainer" style="display: none;">
                        <small class="text-muted">Subcategoria:</small>
                        <div id="subcategoryButtons"></div>
                    </div>

                    <!-- Grid de Produtos (4 por linha) -->
                    <div class="row g-2 mt-2" id="productsGrid">
                        @foreach($products as $product)
                            @php
                                $orderItem = $order->items->firstWhere('product_id', $product->id);
                                $quantity = $orderItem ? $orderItem->quantity : 0;
                            @endphp
                            <div class="col-md-3 product-item" 
                                 data-category="{{ $product->subcategory->category_id ?? '' }}"
                                 data-subcategory="{{ $product->subcategory_id ?? '' }}">
                                <div class="card product-card h-100 {{ $quantity > 0 ? 'selected' : '' }}" data-product-id="{{ $product->id }}">
                                    <div class="product-img">
                                        <i class="align-middle" data-feather="package"></i>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $product->label }}</h6>
                                        <p class="text-muted small mb-4">{{ $product->name }}</p>
                                        
                                        <div class="quantity-controls">
                                            <button type="button" class="btn btn-sm btn-outline-secondary decrease-qty">
                                                <i class="align-middle" data-feather="minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm product-qty" 
                                                   min="0" value="{{ $quantity }}" data-product-id="{{ $product->id }}"
                                                   data-product-name="{{ $product->label }}"
                                                   data-product-price="{{ $product->price }}">
                                            <button type="button" class="btn btn-sm btn-outline-secondary increase-qty">
                                                <i class="align-middle" data-feather="plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Card: Informações Adicionais -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <!-- Trabalhador responsável HIDDEN - atualiza com ID do utilizador logado -->
                    <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="preparacao" {{ $order->status == 'preparacao' ? 'selected' : '' }}>Em Preparação</option>
                            <option value="concluido" {{ $order->status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="entregue" {{ $order->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado do Pagamento <span class="text-danger">*</span></label>
                        <select name="payment_status" id="paymentStatus" class="form-select" required>
                            <option value="nao_pago" {{ $order->payment_status == 'nao_pago' ? 'selected' : '' }}>Não Pago</option>
                            <option value="parcial" {{ $order->payment_status == 'parcial' ? 'selected' : '' }}>Parcial</option>
                            <option value="pago" {{ $order->payment_status == 'pago' ? 'selected' : '' }}>Pago</option>
                        </select>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Observações...">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card: Resumo -->
            <div class="card summary-card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0 text-white">Resumo da Encomenda</h5>
                </div>
                <div class="card-body">
                    <div id="orderSummary" style="display: none;">
                        <div class="text-center text-muted py-4">
                            <i class="align-middle mb-2" data-feather="shopping-cart" style="width: 48px; height: 48px;"></i>
                            <p class="mb-0">Nenhum produto adicionado</p>
                        </div>
                    </div>

                    <div id="orderItems">
                        <!-- Items dinâmicos -->
                    </div>

                    <div id="pricingSection">
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotal">0,00€</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA (22%):</span>
                            <strong id="iva">0,00€</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-primary" id="total">0,00€</h5>
                        </div>
                    </div>

                    <div id="invoiceMessage" style="display: none;">
                        <hr class="my-3">
                        <div class="alert alert-info mb-3">
                            <i class="align-middle" data-feather="info"></i>
                            Valores omitidos pois tem número de fatura
                        </div>
                    </div>

                    <!-- Payment method hidden -->
                    <input type="hidden" name="payment_method" value="">

                    <button type="submit" class="btn btn-warning w-100 btn-lg" id="submitBtn">
                        <i class="align-middle" data-feather="save"></i> Atualizar Encomenda
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedProducts = {};
    let subcategoriesByCategory = @json($products->groupBy('subcategory.category_id')->map(function($items) {
        return $items->pluck('subcategory')->unique('id')->filter()->values();
    }));

    // Inicializar produtos existentes
    document.querySelectorAll('.product-qty').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        if (quantity > 0) {
            selectedProducts[input.dataset.productId] = {
                name: input.dataset.productName,
                price: parseFloat(input.dataset.productPrice),
                quantity: quantity
            };
        }
    });

    // ========================================
    // 1. SELECT2 (CLIENTE)
    // ========================================
    $('#client_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Selecione um cliente...',
        allowClear: true,
        language: {
            noResults: function() { return "Nenhum cliente encontrado"; },
            searching: function() { return "A pesquisar..."; }
        },
        minimumInputLength: 0,
        matcher: function(params, data) {
            if ($.trim(params.term) === '') return data;
            if (params.term.length < 3 && data.id !== '') return null;
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) return data;
            return null;
        }
    });

    $('#client_id').on('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('client_phone').value = option.dataset.phone || '---';
        document.getElementById('client_address').value = option.dataset.address || '---';
    });

    // ========================================
    // 2. FLATPICKR (DATAS)
    // ========================================
    function initDatePicker(inputId, hiddenId) {
        flatpickr(`#${inputId}`, {
            dateFormat: "d/m/Y",
            locale: "pt",
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    const date = selectedDates[0];
                    const formatted = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                    document.getElementById(hiddenId).value = formatted;
                }
            }
        });
    }

    initDatePicker('orderDate', 'orderDateHidden');
    initDatePicker('readyDate', 'readyDateHidden');
    initDatePicker('deliveryDate', 'deliveryDateHidden');
    initDatePicker('desiredDate', 'desiredDateHidden');

    // ========================================
    // 3. LÓGICA DA FATURA
    // ========================================
    const hasInvoiceCheckbox = document.getElementById('hasInvoice');
    const invoiceNumberInput = document.getElementById('invoiceNumber');
    const pricingSection = document.getElementById('pricingSection');
    const invoiceMessage = document.getElementById('invoiceMessage');
    const paymentStatus = document.getElementById('paymentStatus');

    function toggleInvoiceMode(enabled) {
        if (enabled) {
            // Ativar modo fatura
            invoiceNumberInput.classList.add('enabled');
            invoiceNumberInput.required = true;
            invoiceNumberInput.disabled = false;  // ← IMPORTANTE: Ativar input
            pricingSection.style.display = 'none';
            invoiceMessage.style.display = 'block';
            
            // Selecionar "Pago" mas deixar campo DESBLOQUEADO
            paymentStatus.value = 'pago';
            // Forçar a seleção da opção correta
            Array.from(paymentStatus.options).forEach(option => {
                if (option.value === 'pago') {
                    option.selected = true;
                }
            });
        } else {
            // Desativar modo fatura
            invoiceNumberInput.classList.remove('enabled');
            invoiceNumberInput.required = false;
            invoiceNumberInput.disabled = true;  // ← IMPORTANTE: Desativar input
            invoiceNumberInput.value = '';       // ← LIMPAR valor
            pricingSection.style.display = 'block';
            invoiceMessage.style.display = 'none';
            
            // Selecionar "Não Pago" mas deixar campo DESBLOQUEADO
            paymentStatus.value = 'nao_pago';
            // Forçar a seleção da opção correta
            Array.from(paymentStatus.options).forEach(option => {
                if (option.value === 'nao_pago') {
                    option.selected = true;
                }
            });
        }
    }

    hasInvoiceCheckbox.addEventListener('change', function() {
        toggleInvoiceMode(this.checked);
        updateSummary(); // Atualizar resumo para mostrar/esconder valores dos artigos
    });

    // Inicializar estado da fatura
    if (hasInvoiceCheckbox.checked) {
        toggleInvoiceMode(true);
    }

    // ========================================
    // 4. FILTROS CATEGORIA/SUBCATEGORIA
    // ========================================
    document.querySelectorAll('.category-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.category-tabs .nav-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            if (category !== 'all' && subcategoriesByCategory[category]) {
                showSubcategories(category);
            } else {
                hideSubcategories();
                filterProducts(category, 'all');
            }
        });
    });

    function showSubcategories(categoryId) {
        const container = document.getElementById('subcategoryContainer');
        const buttonsDiv = document.getElementById('subcategoryButtons');
        
        container.style.display = 'block';
        buttonsDiv.innerHTML = '';
        
        const allBtn = document.createElement('button');
        allBtn.type = 'button';
        allBtn.className = 'btn btn-sm btn-outline-primary active';
        allBtn.dataset.subcategory = 'all';
        allBtn.textContent = 'Todos';
        buttonsDiv.appendChild(allBtn);
        
        if (subcategoriesByCategory[categoryId]) {
            subcategoriesByCategory[categoryId].forEach(sub => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-outline-primary';
                btn.dataset.subcategory = sub.id;
                btn.textContent = sub.name;
                buttonsDiv.appendChild(btn);
            });
        }
        
        buttonsDiv.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                buttonsDiv.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterProducts(categoryId, this.dataset.subcategory);
            });
        });
        
        filterProducts(categoryId, 'all');
    }

    function hideSubcategories() {
        document.getElementById('subcategoryContainer').style.display = 'none';
    }

    function filterProducts(categoryId, subcategoryId) {
        document.querySelectorAll('.product-item').forEach(item => {
            const itemCategory = item.dataset.category;
            const itemSubcategory = item.dataset.subcategory;
            
            if (categoryId === 'all') {
                item.style.display = 'block';
            } else if (subcategoryId === 'all') {
                item.style.display = itemCategory === categoryId ? 'block' : 'none';
            } else {
                item.style.display = itemSubcategory === subcategoryId ? 'block' : 'none';
            }
        });
        
        if (typeof feather !== 'undefined') feather.replace();
    }

    // ========================================
    // 5. CONTROLES DE QUANTIDADE
    // ========================================
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.product-qty');
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.product-qty');
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    document.querySelectorAll('.product-qty').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(this.value) || 0;
            const card = this.closest('.product-card');

            if (quantity > 0) {
                card.classList.add('selected');
                selectedProducts[productId] = {
                    name: this.dataset.productName,
                    price: parseFloat(this.dataset.productPrice),
                    quantity: quantity
                };
            } else {
                card.classList.remove('selected');
                delete selectedProducts[productId];
            }

            updateSummary();
        });
    });

    // ========================================
    // 6. ATUALIZAR RESUMO
    // ========================================
    function updateSummary() {
        const orderItems = document.getElementById('orderItems');
        const orderSummary = document.getElementById('orderSummary');
        const submitBtn = document.getElementById('submitBtn');
        const hasInvoice = hasInvoiceCheckbox.checked;
        
        const productCount = Object.keys(selectedProducts).length;

        if (productCount === 0) {
            orderSummary.style.display = 'block';
            orderItems.style.display = 'none';
            orderItems.innerHTML = '';
            submitBtn.disabled = true;
            document.getElementById('subtotal').textContent = '0,00€';
            document.getElementById('iva').textContent = '0,00€';
            document.getElementById('total').textContent = '0,00€';
            return;
        }

        orderSummary.style.display = 'none';
        orderItems.style.display = 'block';
        submitBtn.disabled = false;

        let html = '';
        let subtotal = 0;

        for (const [productId, product] of Object.entries(selectedProducts)) {
            const itemTotal = product.price * product.quantity;
            subtotal += itemTotal;
            
            // SE TEM FATURA: Esconde valores (unitário e total)
            if (hasInvoice) {
                html += `
                    <div class="order-item-row">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="flex-grow-1">
                                <strong>${product.name}</strong> × ${product.quantity}
                            </div>
                        </div>
                        <input type="hidden" name="products[${productId}][id]" value="${productId}">
                        <input type="hidden" name="products[${productId}][quantity]" value="${product.quantity}">
                    </div>
                `;
            } else {
                // SEM FATURA: Mostra valores normalmente
                html += `
                    <div class="order-item-row">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="flex-grow-1">
                                <strong>${product.name}</strong> × ${product.quantity}
                            </div>
                            <div class="text-end">
                                <strong>${itemTotal.toFixed(2).replace('.', ',')}€</strong>
                            </div>
                        </div>
                        <small class="text-muted">${product.price.toFixed(2).replace('.', ',')}€ cada</small>
                        <input type="hidden" name="products[${productId}][id]" value="${productId}">
                        <input type="hidden" name="products[${productId}][quantity]" value="${product.quantity}">
                    </div>
                `;
            }
        }

        orderItems.innerHTML = html;

        const iva = subtotal * 0.22;
        const total = subtotal + iva;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2).replace('.', ',') + '€';
        document.getElementById('iva').textContent = iva.toFixed(2).replace('.', ',') + '€';
        document.getElementById('total').textContent = total.toFixed(2).replace('.', ',') + '€';
    }

    // Atualizar sumário inicial
    updateSummary();

    // Feather Icons
    if (typeof feather !== 'undefined') feather.replace();
});
</script>
@endpush
