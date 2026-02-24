@extends('layouts.site')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <h1 class="text-center mb-5">Contact Us</h1>

    <div class="mb-5">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore dolores ipsa molestiae nemo, officiis tenetur! Dicta, necessitatibus numquam pariatur perferendis quisquam quo. Ab facilis ipsa necessitatibus? Necessitatibus, provident repudiandae!</p>
    </div>

    <form>
        <div class="mb-2">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="messages" class="form-label">Messages</label>
            <textarea class="form-control" id="messages" rows="3"></textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="" id="terms">
            <label class="form-check-label" for="terms">I Agree to Terms</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>
@endsection
