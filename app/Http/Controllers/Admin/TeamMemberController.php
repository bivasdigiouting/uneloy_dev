<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeamMemberController extends Controller
{
    protected TeamMemberRepositoryInterface $teamMemberRepository;

    public function __construct(TeamMemberRepositoryInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function index()
    {
        $teamMembers = $this->teamMemberRepository->getAll();
        return view('admin.about-us.our-team.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('admin.about-us.our-team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'status' => 'boolean',
        ]);

        try {
            $validated['status'] = $request->has('status');
            $this->teamMemberRepository->create($validated);
            return redirect()->route('admin.our-team.index')->with('success', 'Team Member added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create Team Member: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while adding the team member. Please try again.');
        }
    }

    public function edit($id)
    {
        $teamMember = $this->teamMemberRepository->getById($id);
        return view('admin.about-us.our-team.edit', compact('teamMember'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'email' => 'nullable|email|max:255',
            'contact_no' => 'nullable|string|max:20',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'status' => 'boolean',
        ]);

        try {
            $validated['status'] = $request->has('status');
            $this->teamMemberRepository->update($id, $validated);
            return redirect()->route('admin.our-team.index')->with('success', 'Team Member updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Team Member: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the team member. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->teamMemberRepository->delete($id);
            return redirect()->route('admin.our-team.index')->with('success', 'Team Member deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete Team Member: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the team member. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $teamMember = $this->teamMemberRepository->toggleStatus($id);
            return response()->json(['success' => true, 'status' => $teamMember->status]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle Team Member status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while updating status.']);
        }
    }
}
