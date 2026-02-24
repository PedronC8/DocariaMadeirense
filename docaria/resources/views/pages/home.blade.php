@extends('layouts.site')

@section('title', 'Home')

@section('content')
<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1600&q=80" class="d-block w-100 hero-placeholder object-fit-cover" alt="Car 1">
            <div class="carousel-caption d-none d-md-block">
                <h5>First slide label</h5>
                <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1493238792000-8113da705763?auto=format&fit=crop&w=1600&q=80" class="d-block w-100 hero-placeholder object-fit-cover" alt="Car 2">
            <div class="carousel-caption d-none d-md-block">
                <h5>Second slide label</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=1600&q=80" class="d-block w-100 hero-placeholder object-fit-cover" alt="Car 3">
            <div class="carousel-caption d-none d-md-block">
                <h5>Third slide label</h5>
                <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<div class="container py-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Card 1">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary btn-sm">Go somewhere</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Card 2">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary btn-sm">Go somewhere</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <img src="https://images.unsplash.com/photo-1617531653332-bd46c24f2068?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Card 3">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary btn-sm">Go somewhere</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-center mb-4">
        <div class="col-md-6">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
        </div>
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1504215680853-026ed2a45def?auto=format&fit=crop&w=1000&q=80" class="img-fluid" alt="Red car">
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1000&q=80" class="img-fluid" alt="Blue car">
        </div>
        <div class="col-md-6">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
        </div>
    </div>
</div>
@endsection
