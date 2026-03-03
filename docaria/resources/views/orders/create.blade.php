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
        <h3><strong>Nova Encomenda</strong></h3>
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
        <!-- Área Principal - Produtos e Informações -->
        <div class="col-lg-8">
            <!-- Card: Informações do Cliente -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Selecione um cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            data-phone="{{ $client->contact }}"
                                            data-address="{{ $client->address }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" id="client_phone" class="form-control" readonly placeholder="---">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Morada</label>
                            <input type="text" id="client_address" class="form-control" readonly placeholder="---">
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
                                   value="{{ old('desired_date') ? \Carbon\Carbon::parse(old('desired_date'))->format('d/m/Y') : '' }}"
                                   autocomplete="off">
                            <input type="hidden" name="desired_date" id="desiredDateHidden" 
                            value="{{ old('desired_date', date('Y-m-d')) }}">
                        </div>
                    </div>
                    
                    <!-- Campos Hidden - preenchidos automaticamente com data atual -->
                    <input type="hidden" name="ready_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="delivery_date" value="{{ date('Y-m-d') }}">
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
                                                   min="0" value="0" data-product-id="{{ $product->id }}"
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

        <!-- Sidebar - Informações Adicionais + Resumo -->
        <div class="col-lg-4">
            <!-- Card: Informações Adicionais -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <!-- Trabalhador responsável HIDDEN - usa o ID do utilizador logado -->
                    <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="preparacao" selected>Em Preparação</option>
                            <option value="concluido">Concluído</option>
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
                <div class="card-header text-white" style="background-color: #222e3c;">
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
        minimumInputLength: 0,
        matcher: function(params, data) {
            if ($.trim(params.term) === '') return data;
            if (params.term.length < 1 && data.id !== '') return null;
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) return data;
            return null;
        }
    });

    // Atualizar informações do cliente
    $('#client_id').on('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('client_phone').value = option.dataset.phone || '---';
        document.getElementById('client_address').value = option.dataset.address || '---';
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
        defaultDate: "today",
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

    // ========================================
    // 3. FILTRAR PRODUTOS POR CATEGORIA
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

        orderItems.innerHTML = html;

        const iva = subtotal * 0.22;  // 22% IVA Madeira
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
