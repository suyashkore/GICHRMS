<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class ProfileController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function getProfile(Request $request) {
		try {
			$user = $request->user();

			return response()->json([
				'status' => true,
				'message' => 'Profile fetched successfully.',
				'data' => [
					'staffid' => $user->staffid,
					'email' => $user->email,
					'username' => $user->username,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'phonenumber' => $user->phonenumber,
					'facebook' => $user->facebook,
					'linkedin' => $user->linkedin,
					'skype' => $user->skype,
					'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
					'last_login' => $user->last_login
				]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function updateProfile(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'firstname' => 'required|string|max:50',
				'lastname' => 'required|string|max:50',
				'phonenumber' => 'required|string|max:20',
				'facebook' => 'nullable|url|max:255',
				'linkedin' => 'nullable|url|max:255',
				'skype' => 'nullable|url|max:255',
			]);

			$user->update($request->only('firstname', 'lastname', 'phonenumber', 'facebook', 'linkedin', 'skype'));

			return response()->json([
				'status' => true,
				'message' => 'Profile updated successfully.',
				'data' => $user
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
	public function changePhoto(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
				], [
						'photo.required' => 'Profile photo is required.',
						'photo.image'    => 'Uploaded file must be an image.',
						'photo.mimes'    => 'Only JPG, JPEG, PNG, and WEBP formats are allowed.',
						'photo.max'      => 'Image size must not exceed 2 MB.',
				]);

				if ($validator->fails()) {
						return response()->json([
								'status'  => false,
								'message' => $validator->errors()->first(),
								'data'    => null
						], 422);
				}

				$user = $request->user();

				if (!$request->hasFile('photo')) {
						return response()->json([
								'status'  => false,
								'message' => 'No image file received.',
								'data'    => null
						], 400);
				}

				$file = $request->file('photo');

				// Optional: delete old image if exists
				if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
						Storage::disk('public')->delete($user->profile_image);
				}

				$fileName = 'staff_' . $user->staffid . '_' . time() . '.' . $file->getClientOriginalExtension();

				$filePath = $file->storeAs('profile_photos', $fileName, 'public');

				// Save in DB
				$user->profile_image = $filePath;
				$user->save();

				return response()->json([
						'status'  => true,
						'message' => 'Profile photo updated successfully.',
						'data'    => [
								'photo_path' => $filePath,
								'photo_url'  => asset('storage/' . $filePath)
						]
				]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function changePassword(Request $request) {
		$request->validate([
			'old_password' => 'required|string',
			'new_password' => 'required|string|min:8|confirmed',
		]);

		try {
			$user = $request->user();

			// Convert stored CI3 hash ($2a$) to Laravel compatible ($2y$)
			$storedHash = $user->password;

			if (strpos($storedHash, '$2a$') === 0) {
					$storedHash = '$2y$' . substr($storedHash, 4);
			}

			// Verify old password
			if (!Hash::check($request->old_password, $storedHash)) {
					return response()->json([
							'status'  => false,
							'message' => 'Old password is incorrect.',
							'data'    => null
					], 400);
			}

			// Prevent same password reuse
			if ($request->old_password === $request->new_password) {
					return response()->json([
							'status'  => false,
							'message' => 'New password must be different from old password.',
							'data'    => null
					], 400);
			}

			// Save new password in CI3 format ($2a$ + cost 8)
			$hashedPassword = password_hash($request->new_password, PASSWORD_BCRYPT, [
					'cost' => 8
			]);

			$hashedPassword = '$2a$' . substr($hashedPassword, 4);

			$user->password = $hashedPassword;
			$user->save();

			return response()->json([
					'status'  => true,
					'message' => 'Password changed successfully.'
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
