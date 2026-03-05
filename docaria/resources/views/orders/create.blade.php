@extends('layouts.app')

@section('title', 'Nova Encomenda - A Docaria')

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
        overflow: hidden;
        border-radius: 0.25rem 0.25rem 0 0;
        background: #eef2f6;
    }
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .product-img-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: auto;
    }
    .quantity-controls button {
        width: 56px;
        height: 36px;
        padding: 0;
        font-size: 1.2rem;
        line-height: 1;
    }
    .quantity-controls input {
        width: 56px;
        height: 36px;
        text-align: center;
        font-size: 1rem;
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
        display: flex;
        flex-direction: column;
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
    /* Corrigir altura do Select2 */
    .select2-container--bootstrap-5 .select2-selection--single {
        min-height: 33px !important;
        padding: 0.375rem 0.75rem;
    }
    /* Garantir que o texto do cliente nÃ£o tapa o botÃ£o de limpar (x) */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-right: 3rem !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong style="color: #2f4f6c;">Nova Encomenda</strong></h3>
    </div>
    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="align-middle" data-feather="arrow-left"></i> Voltar
        </a>
    </div>
</div>

<form id="orderForm" method="POST" action="{{ route('orders.store') }}">
    @csrf
    <div class="row">
        <!-- Ãrea Principal - Produtos e InformaÃ§Ãµes -->
        <div class="col-lg-8">
            <!-- Card: InformaÃ§Ãµes do Cliente -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Selecione um cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Datas (Simplificado) -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Data Encomenda <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="orderDate" 
                                   class="form-control datepicker" 
                                   placeholder="dd/mm/aaaa"
                                   value="{{ old('order_date') ? \Carbon\Carbon::parse(old('order_date'))->format('d/m/Y') : date('d/m/Y') }}"
                                   autocomplete="off" 
                                   required>
                            <input type="hidden" name="order_date" id="orderDateHidden" value="{{ old('order_date', date('Y-m-d')) }}">
                            @error('order_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data Entrega Desejada</label>
                            <input type="text" 
                                   id="desiredDate" 
                                   class="form-control datepicker" 
                                   placeholder="dd/mm/aaaa"
                                   value="{{ old('desired_date') ? \Carbon\Carbon::parse(old('desired_date'))->format('d/m/Y') : date('d/m/Y') }}"
                                   autocomplete="off">
                            <input type="hidden" name="desired_date" id="desiredDateHidden" 
                            value="{{ old('desired_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                    
                    <!-- Campos Hidden - preenchidos automaticamente com data atual -->
                    <input type="hidden" name="ready_date" value="{{ old('ready_date', date('Y-m-d')) }}">
                    <input type="hidden" name="delivery_date" value="{{ old('delivery_date', date('Y-m-d')) }}">
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
                                <button class="nav-link" data-category="{{ $category->id }}" data-category-name="{{ $category->name }}" type="button">
                                    {{ $category->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Subcategorias (aparecem conforme categoria selecionada) -->
                    <div class="subcategory-tabs" id="subcategoryContainer" style="display: none;">
                        <small class="text-muted">Subcategoria:</small>
                        <div id="subcategoryButtons"></div>
                    </div>

                    <!-- Grid de Produtos (4 por linha) -->
                    <div class="row g-2 mt-2" id="productsGrid">
                        @foreach($products as $product)
                            <div class="col-md-3 product-item" 
                                 data-category="{{ $product->subcategory->category_id ?? '' }}"
                                 data-subcategory="{{ $product->subcategory_id ?? '' }}">
                                <div class="card product-card h-100" data-product-id="{{ $product->id }}">
                                    <div class="product-img">
                                        @if(!empty($product->image_path))
                                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}" loading="lazy">
                                        @else
                                            <div class="product-img-fallback">
                                                <i class="align-middle" data-feather="package"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $product->label }}</h6>
                                        <p class="text-muted small mb-4">{{ $product->name }}</p>
                                        
                                        <div class="quantity-controls">
                                            <button type="button" class="btn btn-sm btn-outline-secondary decrease-qty">
                                                <i class="align-middle" data-feather="minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm product-qty" 
                                                   min="0" value="0" data-product-id="{{ $product->id }}"
                                                   data-product-name="{{ $product->name }}"
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

        <!-- Sidebar - InformaÃ§Ãµes Adicionais + Resumo -->
        <div class="col-lg-4">
            <!-- Card: InformaÃ§Ãµes Adicionais -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <!-- Trabalhador responsÃ¡vel HIDDEN - usa o ID do utilizador logado -->
                    <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="preparacao" selected>Em Preparação</option>
                            <option value="concluido">Concluí­do</option>
                            <option value="entregue">Entregue</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado do Pagamento <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            <option value="nao_pago" selected>Não Pago</option>
                            <option value="parcial">Parcial</option>
                            <option value="pago">Pago</option>
                        </select>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Observações...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card: Resumo da Encomenda -->
            <div class="card summary-card">
                <div class="card-header text-white" style="background-color: #2f4f6c;">
                    <h5 class="card-title mb-0 text-white">Resumo da Encomenda</h5>
                </div>
                <div class="card-body">
                    <div id="orderSummary">
                        <div class="text-center text-muted py-4">
                            <i class="align-middle mb-2" data-feather="shopping-cart" style="width: 48px; height: 48px;"></i>
                            <p class="mb-0">Nenhum produto adicionado</p>
                        </div>
                    </div>

                    <div id="orderItems" style="display: none;">
                        <!-- Items serão adicionados aqui dinamicamente -->
                    </div>

                    <div class="d-flex justify-content-between mb-2" style="display: none !important;">
                        <span>Subtotal:</span>
                        <strong id="subtotal">0,€</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="display: none !important;">
                        <span>IVA:</span>
                        <strong id="iva">0,00€</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0 text-primary" id="total">0,00€</h5>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg" id="submitBtn" disabled>
                        <i class="align-middle" data-feather="check"></i> Submeter Encomenda
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

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedProducts = {};
    let subcategoriesByCategory = @json($products->groupBy('subcategory.category_id')->map(function($items) {
        return $items->pluck('subcategory')->unique('id')->filter()->values();
    }));

    // ========================================
    // 1. INICIALIZAR SELECT2 (CLIENTE)
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
    });

    // ========================================
    // 2. INICIALIZAR FLATPICKR (DATAS)
    // ========================================
    flatpickr("#orderDate", {
        dateFormat: "d/m/Y",
        locale: "pt",
        defaultDate: "today",
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const date = selectedDates[0];
                const formatted = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                document.getElementById('orderDateHidden').value = formatted;
            }
        }
    });

    flatpickr("#desiredDate", {
        dateFormat: "d/m/Y",
        locale: "pt",
        defaultDate: document.getElementById('desiredDate').value || null,
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const date = selectedDates[0];
                const formatted = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                document.getElementById('desiredDateHidden').value = formatted;
            } else {
                document.getElementById('desiredDateHidden').value = '';
            }
        }
    });

    function parsePtDateToIso(value) {
        if (!value) return '';
        const parts = value.split('/');
        if (parts.length !== 3) return '';
        const [day, month, year] = parts;
        if (!day || !month || !year) return '';
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    }

    function syncDateField(inputId, hiddenId) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !hidden) return;

        const iso = parsePtDateToIso(input.value.trim());
        hidden.value = iso || hidden.value || '';
    }

    document.getElementById('orderDate').addEventListener('change', function() {
        syncDateField('orderDate', 'orderDateHidden');
    });

    document.getElementById('desiredDate').addEventListener('change', function() {
        syncDateField('desiredDate', 'desiredDateHidden');
    });

    document.getElementById('orderForm').addEventListener('submit', function() {
        syncDateField('orderDate', 'orderDateHidden');
        syncDateField('desiredDate', 'desiredDateHidden');
    });

    // ========================================
    // 3. FILTRAR PRODUTOS POR CATEGORIA
    // ========================================
    function normalizeCategoryName(name) {
        return (name || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
    }

    function shouldHideSubcategory(name) {
        const normalized = normalizeCategoryName(name);
        return normalized === 'broas' || normalized === 'rebucados';
    }

    document.querySelectorAll('.category-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.category-tabs .nav-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            const categoryName = this.dataset.categoryName || '';
            
            if (category !== 'all' && subcategoriesByCategory[category] && !shouldHideSubcategory(categoryName)) {
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
    // 4. CONTROLES DE QUANTIDADE
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
            const productName = this.dataset.productName;
            const productPrice = parseFloat(this.dataset.productPrice);
            
            const card = this.closest('.product-card');

            if (quantity > 0) {
                card.classList.add('selected');
                selectedProducts[productId] = {
                    name: productName,
                    price: productPrice,
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
    // 5. ATUALIZAR RESUMO
    // ========================================
    function updateSummary() {
        const orderItems = document.getElementById('orderItems');
        const orderSummary = document.getElementById('orderSummary');
        const submitBtn = document.getElementById('submitBtn');
        
        const productCount = Object.keys(selectedProducts).length;

        if (productCount === 0) {
            orderSummary.style.display = 'block';
            orderItems.style.display = 'none';
            orderItems.innerHTML = '';
            submitBtn.disabled = true;
            document.getElementById('subtotal').textContent = '0,00Ã¢â€šÂ¬';
            document.getElementById('iva').textContent = '0,00Ã¢â€šÂ¬';
            document.getElementById('total').textContent = '0,00Ã¢â€šÂ¬';
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
            
            html += `
                <div class="order-item-row">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="flex-grow-1">
                            ${product.name} x ${product.quantity}
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

        orderItems.innerHTML = html;

        // Em nova encomenda, sem Nº Fatura ativo, IVA fica a 0 por defeito.
        const iva = 0;
        const total = subtotal + iva;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2).replace('.', ',') + '€';
        document.getElementById('iva').textContent = iva.toFixed(2).replace('.', ',') + '€';
        document.getElementById('total').textContent = total.toFixed(2).replace('.', ',') + '€';
    }

    // Inicializar Feather Icons
    if (typeof feather !== 'undefined') feather.replace();
});
</script>
@endpush







