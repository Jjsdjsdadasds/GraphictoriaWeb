@extends('layouts.app')

@section('title', $title)

@section('page-specific')
<script src="{{ mix('js/Item.js') }}"></script>
@endsection

@section('content')
<div class="container mx-auto py-5">
	@if(!$asset->approved)
		<div class="alert alert-danger text-center"><strong>This asset is pending approval.</strong> It will not appear in-game and cannot be voted on or purchased at this time.</div>
	@endif
	<div id="gt-item" class="graphictoria-item-page"
		@auth
			data-asset-id="{{ $asset->id }}"
			data-asset-name="{{ $asset->name }}"
			data-asset-creator="{{ $asset->user->username }}"
			data-asset-type="{{ $asset->typeString() }}"
			data-asset-on-sale="{{ $asset->onSale }}"
			@if ($asset->onSale)
				data-asset-price="{{ $asset->priceInTokens }}"
				data-user-currency="{{ Auth::user()->tokens }}"
				data-can-afford="{{ $asset->priceInTokens <= Auth::user()->tokens }}"
			@endif
		@endauth
	>
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="d-flex">
					<div class="pe-4">
						<img src={{ asset('images/testing/hat.png') }} alt="{{ $asset->name }}" class="border img-fluid" />
					</div>
					<div class="flex-fill">
						<h3 class="mb-0">{{ $asset->name }}</h3>
						{{-- TODO: XlXi: url to user's profile --}}
						<p>By {{ $asset->user->username }}</p>
						<hr />
						{{-- TODO: XlXi: limiteds/trading --}}
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Price</p>
							</div>
							<div class="col-9 d-flex">
								@if( $asset->onSale )
									<h4 class="my-auto" style="color:#e59800!important;font-weight:bold">
										<img src="{{ asset('images/symbols/token.svg') }}" height="32" width="32" class="img-fluid me-1" style="margin-top:-1px" />{{ number_format($asset->priceInTokens) }}</h4>
									</h4>
								@endif
								@auth
									<div id="gt-purchase-button" class="ms-auto">
										<button class="px-5 btn btn-lg btn-success" disabled>Buy</button>
									</div>
								@endauth
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Type</p>
							</div>
							<div class="col-9">
								<p>{{ $asset->typeString() }}</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Description</p>
							</div>
							<div class="col-9">
								@if ( $asset->description )
									<p>{{ $asset->description }}</p>
								@else
									<p class="text-muted">This item has no description.</p>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="gt-comments"
			data-can-comment="{{ intval(Auth::check()) }}"
			data-asset-id="{{ $asset->id }}"
		></div>
	</div>
</div>
@endsection