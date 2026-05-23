<?php

namespace App\Repositories;

use App\Models\TeamMember;
use App\Repositories\Interfaces\TeamMemberRepositoryInterface;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    public function getAll()
    {
        return TeamMember::orderBy('created_at', 'desc')->get();
    }

    public function getActive()
    {
        return TeamMember::where('status', true)->orderBy('created_at', 'desc')->get();
    }

    public function getById($id)
    {
        return TeamMember::findOrFail($id);
    }

    public function create(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $image = $data['image'];
            $imageName = 'team_member_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/team'), $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }

        return TeamMember::create($data);
    }

    public function update($id, array $data)
    {
        $teamMember = $this->getById($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($teamMember->image && file_exists(public_path($teamMember->image))) {
                unlink(public_path($teamMember->image));
            }

            $image = $data['image'];
            $imageName = 'team_member_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/team'), $imageName);
            $data['image'] = 'uploads/team/' . $imageName;
        }

        $teamMember->update($data);
        return $teamMember;
    }

    public function delete($id)
    {
        $teamMember = $this->getById($id);
        
        if ($teamMember->image && file_exists(public_path($teamMember->image))) {
            unlink(public_path($teamMember->image));
        }

        return $teamMember->delete();
    }

    public function toggleStatus($id)
    {
        $teamMember = $this->getById($id);
        $teamMember->status = !$teamMember->status;
        $teamMember->save();
        return $teamMember;
    }
}
