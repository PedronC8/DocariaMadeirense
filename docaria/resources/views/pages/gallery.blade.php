@extends('layouts.site')

@section('title', 'Gallery')

@section('content')
<div class="container-fluid px-3 py-4">
    <section class="bg-body-secondary text-center py-5 mb-4 rounded">
        <h1 class="display-5">Check our gallery</h1>
        <p class="mb-3">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple's marketing pages.</p>
        <a href="#" class="btn btn-outline-primary">View now</a>
    </section>

    <div class="row g-3">
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1494905998402-395d579af36f?auto=format&fit=crop&w=700&q=80" alt="Gallery 1"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1514316454349-750a7fd3da3a?auto=format&fit=crop&w=700&q=80" alt="Gallery 2"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1542282088-fe8426682b8f?auto=format&fit=crop&w=700&q=80" alt="Gallery 3"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=700&q=80" alt="Gallery 4"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&w=700&q=80" alt="Gallery 5"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=700&q=80" alt="Gallery 6"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=700&q=80" alt="Gallery 7"></div>
        <div class="col-6 col-md-3"><img class="img-fluid w-100 h-100 object-fit-cover" src="https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=700&q=80" alt="Gallery 8"></div>
    </div>
</div>
@endsection
