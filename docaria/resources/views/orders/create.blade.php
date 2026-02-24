@extends('layouts.app')

@section('title', 'Nova Encomenda - A Docaria')

@push('styles')
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
        height: 120px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .quantity-controls button {
        width: 32px;
        height: 32px;
        padding: 0;
        font-size: 1.2rem;
        line-height: 1;
    }
    .quantity-controls input {
        width: 60px;
        text-align: center;
    }
    .category-tabs .nav-link {
        cursor: pointer;
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

            <!-- Card: Datas -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Data Encomenda <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" 
                                   value="{{ old('order_date', date('Y-m-d')) }}" required>
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pronto Em <span class="text-danger">*</span></label>
                            <input type="date" name="ready_date" class="form-control @error('ready_date') is-invalid @enderror" 
                                   value="{{ old('ready_date') }}" required>
                            @error('ready_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Entrega <span class="text-danger">*</span></label>
                            <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   value="{{ old('delivery_date') }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Desejada</label>
                            <input type="date" name="desired_date" class="form-control" value="{{ old('desired_date') }}">
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
                    <ul class="nav nav-tabs category-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-category="all" type="button">Todos</button>
                        </li>
                        @php
                            $categories = $products->pluck('subcategory.category')->unique('id');
                        @endphp
                        @foreach($categories as $category)
                            @if($category)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-category="{{ $category->id }}" type="button">
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <!-- Grid de Produtos -->
                    <div class="row g-3" id="productsGrid">
                        @foreach($products as $product)
                            <div class="col-md-4 product-item" data-category="{{ $product->subcategory->category_id ?? '' }}">
                                <div class="card product-card h-100" data-product-id="{{ $product->id }}">
                                    <div class="product-img">
                                        <i class="align-middle" data-feather="package"></i>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $product->label }}</h6>
                                        <p class="text-muted small mb-2">{{ $product->name }}</p>
                                        <p class="text-primary fw-bold mb-3">{{ $product->formatted_price }}</p>
                                        
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

            <!-- Card: Informações Adicionais -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trabalhador Responsável</label>
                            <select name="trabalhador_id" class="form-select">
                                <option value="">Nenhum</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="preparacao" selected>Em Preparação</option>
                                <option value="concluido">Concluído</option>
                                <option value="entregue">Entregue</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Método de Pagamento</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Selecione...</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao">Cartão</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Observações...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Resumo da Encomenda -->
        <div class="col-lg-4">
            <div class="card summary-card">
                <div class="card-header bg-primary text-white">
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
                        <span>IVA (23%):</span>
                        <strong id="iva">0,00€</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0 text-primary" id="total">0,00€</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado do Pagamento <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            <option value="nao_pago" selected>Não Pago</option>
                            <option value="parcial">Parcial</option>
                            <option value="pago">Pago</option>
                        </select>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedProducts = {};

    // Atualizar informações do cliente
    const clientSelect = document.getElementById('client_id');
    clientSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('client_phone').value = option.dataset.phone || '---';
        document.getElementById('client_address').value = option.dataset.address || '---';
    });

    // Filtrar produtos por categoria
    document.querySelectorAll('.category-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.category-tabs .nav-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            document.querySelectorAll('.product-item').forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Reinicializar Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    });

    // Controles de quantidade
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

    // Atualizar ao mudar quantidade
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

        const iva = subtotal * 0.23;
        const total = subtotal + iva;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2).replace('.', ',') + '€';
        document.getElementById('iva').textContent = iva.toFixed(2).replace('.', ',') + '€';
        document.getElementById('total').textContent = total.toFixed(2).replace('.', ',') + '€';
    }

    // Inicializar Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
