@extends('layouts.admin')

@section('title', 'User Admin (' . $user->username . ')')

@section('page-specific')
	<style>
		.vb-admin-divider > *:first-child {
			border-top: 0!important;
		}
	</style>
@endsection

@push('content')
	<div class="container-md">
		<x-admin.navigation.user-admin :uid="$user->id" />
		<div class="row">
			<div class="col-8">
				<h4>Account Summary</h4>
				<div class="card me-2 px-3 vb-admin-divider">
					@php
						$isProtected = false;
						$isPowerful = false;
						$powerColor = 'secondary';
						$powerType = '[ Error ]';
						
						if($user->hasRoleset('ProtectedUser'))
							$isProtected = true;
						
						if($user->hasRoleset('Owner'))
						{
							$isPowerful = true;
							$powerColor = 'danger';
							$powerType = 'owner';
						}
						elseif($user->hasRoleset('Administrator'))
						{
							$isPowerful = true;
							$powerColor = 'primary';
							$powerType = 'administrator';
						}
						elseif($user->hasRoleset('Moderator'))
						{
							$isPowerful = true;
							$powerColor = 'success';
							$powerType = 'moderator';
						}
					@endphp
					@if($isProtected)
						<div class="alert alert-danger virtubrick-alert virtubrick-error-popup mt-3">This user is protected.</div>
					@endif
					@if($isPowerful)
						<div @class([
							'alert',
							'alert-' . $powerColor,
							'virtubrick-alert',
							'virtubrick-error-popup',
							'mt-3' => !$isProtected
						])>
							This user is a(n) {{ $powerType }}.
						</div>
					@endif
					<x-admin.user-admin-label label="Username">{{ $user->username }}</x-admin.user-admin-label>
					@php
						$prevUsernames = \App\Models\Username::where('user_id', $user->id)
													->where('username', '!=', $user->username);
					@endphp
					@if($prevUsernames->count() > 0)
						<x-admin.user-admin-label label="Previous User Names">
							@foreach($prevUsernames->get() as &$userName)
								<p>{{ $userName->username }} ({{ $userName->created_at->isoFormat('lll') }})</p>
							@endforeach
						</x-admin.user-admin-label>
					@endif
					<x-admin.user-admin-label label="Moderation Status"><x-admin.moderation-status :user="$user" /></x-admin.user-admin-label>
					<x-admin.user-admin-label label="User Id">
						<x-admin.user-search-input id="userid" definition="User ID" :value="$user->id" :nolabel=true />
					</x-admin.user-admin-label>
					<x-admin.user-admin-label label="Username">
						<x-admin.user-search-input id="username" definition="Username" :value="$user->username" :nolabel=true />
					</x-admin.user-admin-label>
					<x-admin.user-admin-label label="Current Location">Website <b>TODO</b></x-admin.user-admin-label>
					<div class="row py-2 border-top">
						<div class="col-6">
							<img src="{{ $user->getImageUrl() }}" width="200" height="200" class="img-fluid vb-charimg" />
						</div>
						<div class="col-6">
							<a href="{{ $user->getProfileUrl() }}" class="text-decoration-none">User Homepage</a><br/>
							<a href="#" class="text-decoration-none">Moderate User</a>
						</div>
					</div>
				</div>
				
				<h4 class="mt-3">Punishments</h4>
				<x-admin.user-punishments :user="$user" />
				
				<h4 class="mt-3">Seller Ban</h4>
				<div class="card me-2 p-3 pt-0 vb-admin-divider">
					<x-admin.user-admin-label label="Current Ban">None (todo)</x-admin.user-admin-label>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="seller-ban" id="seller-unban">
						<label class="form-check-label" for="seller-unban">Unban</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="seller-ban" id="seller-1dayban">
						<label class="form-check-label" for="seller-1dayban">1 Day</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="seller-ban" id="seller-3dayban">
						<label class="form-check-label" for="seller-3dayban">3 Days</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="seller-ban" id="seller-permban">
						<label class="form-check-label" for="seller-permban">Forever</label>
					</div>
					<label for="seller-note" class="form-label">Note:</label>
					<textarea type="text" class="form-control" id="seller-note" placeholder="Seller ban note."></textarea>
					<button type="submit" class="btn btn-danger mt-1">Submit Seller Ban</button>
				</div>
			</div>
			
			<div class="col-4">
				<div class="d-flex">
					<h4>User Notes</h4>
					<button class="btn btn-sm btn-primary ms-auto"><i class="fa-solid fa-circle-plus"></i> Add New</button>
				</div>
				<div class="card px-3 vb-admin-divider">
					<div class="border-top py-2">
						<span class="badge bg-primary">XlXi</span>
						<span class="badge bg-secondary">12/28/2022 5:35 PM</span>
						<span class="badge bg-secondary">#1</span>
						<p class="my-1">Example note.</p>
					</div>
					<div class="border-top py-2">
						<span class="badge bg-primary">XlXi</span>
						<span class="badge bg-secondary">12/28/2022 5:35 PM</span>
						<span class="badge bg-secondary">#1</span>
						<p class="my-1">Example note.</p>
					</div>
				</div>
				
				<h4 class="mt-3">Update User</h4>
				<div class="card me-2 p-3 pt-0 vb-admin-divider">
					@owner
						<x-admin.user-admin-label label="User Name">todo put a form here to update</x-admin.user-admin-label>
					@endowner
					@admin
						<x-admin.user-admin-label label="User Blurb"><a href="#" class="text-decoration-none">(scrub) (todo)</a></x-admin.user-admin-label>
					@endadmin
					@owner
						<x-admin.user-admin-label label="Email">todo put a form here to update</x-admin.user-admin-label>
						<x-admin.user-admin-label label="Password">todo put a form here to update</x-admin.user-admin-label>
					@endowner
					<x-admin.user-admin-label label="Facebook Url">Todo Link and disconnect/or Not Connected</x-admin.user-admin-label>
					<x-admin.user-admin-label label="Twitter Url">Todo Link and disconnect/or Not Connected</x-admin.user-admin-label>
					<x-admin.user-admin-label label="YouTube Url">Todo Link and disconnect/or Not Connected</x-admin.user-admin-label>
					<x-admin.user-admin-label label="Twitch Url">Todo Link and disconnect/or Not Connected</x-admin.user-admin-label>
					<x-admin.user-admin-label label="User Created">{{ $user->created_at->isoFormat('l LT') }}</x-admin.user-admin-label>
					<x-admin.user-admin-label label="Last Location">Website (todo)</x-admin.user-admin-label>
					<x-admin.user-admin-label label="User Activity">{{ $user->last_seen->isoFormat('l LT') }}</x-admin.user-admin-label>
					@owner
						<button type="submit" class="btn btn-warning">Update User</button>
					@endowner
				</div>
				
				<h4 class="mt-3">Badges and Settings</h4>
				<div class="card me-2 px-3 vb-admin-divider">
					<x-admin.user-admin-label label="Tokens">{{ $user->tokens }}</x-admin.user-admin-label>
				</div>
			</div>
		</div>
	</div>
@endpush