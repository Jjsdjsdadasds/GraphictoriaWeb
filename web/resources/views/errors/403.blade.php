@extends('layouts.app')

@section('title', 'Forbidden')

@section('content')
<div class="container virtubrick-center-vh">
	<x-card title="FORBIDDEN">
		<x-slot name="body">
			You don't have permission to view this page. If you believe this is an error, contact us at <a href="mailto:support@virtubrick.net" class="fw-bold text-decoration-none">support@virtubrick.net</a>!
		</x-slot>
		<x-slot name="footer">
			<div class="mt-2">
				<a class="btn btn-primary px-4 me-2" href="{{ url('/') }}">Home</a>
				<a class="btn btn-secondary px-4" onclick="history.back();return false;">Back</a>
			</div>
		</x-slot>
	</x-card>
</div>
@endsection
