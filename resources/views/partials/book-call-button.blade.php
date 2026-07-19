@php($buttonClass = $class ?? 'btn btn-outline')
<a href="#" class="{{ $buttonClass }} book-call-btn" data-request-type="book_call">{{ $label ?? 'Book a Call' }}</a>
