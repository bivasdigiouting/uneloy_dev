<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Repositories\Interfaces\BannerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *   name="Banners",
 *   description="Banner REST API (view-only endpoints in docs)"
 * )
 */
class BannerController extends Controller
{
    protected BannerRepositoryInterface $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Banners
     * List banners with optional filters.
     *
     * Filters: `banner_type`, `status`.
     *
     * @group Banners
     *
     * @unauthenticated
     *
     * @queryParam banner_type string Filter by banner type. Example: home_1.
     * @queryParam status string Filter by status. Example: active.
     *
     * @response 200 {"data": [{"id":1,"banner_type":"home_1","image":null,"link":null,"status":"active"}]}
     */
    /**
     * @OA\Get(
     *   path="/api/v1/banners",
     *   summary="List banners",
     *   tags={"Banners"},
     *
     *   @OA\Parameter(name="banner_type", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string")),
     *
     *   @OA\Response(response=200, description="OK",
     *
     *     @OA\JsonContent(type="object",
     *
     *       @OA\Property(property="data", type="array",
     *
     *         @OA\Items(type="object",
     *
     *           @OA\Property(property="id", type="integer"),
     *           @OA\Property(property="banner_type", type="string"),
     *           @OA\Property(property="image", type="string", nullable=true),
     *           @OA\Property(property="link", type="string", nullable=true),
     *           @OA\Property(property="status", type="string")
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $query = Banner::query();
        if ($request->filled('banner_type')) {
            $query->where('banner_type', $request->get('banner_type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        $banners = $query->orderByDesc('created_at')->get(['id', 'banner_type', 'image', 'link', 'status']);

        return response()->json(['data' => $banners]);
    }

    /**
     * Banner Details
     * Get a banner by ID.
     *
     * @group Banners
     *
     * @unauthenticated
     *
     * @response 200 {"id":1,"banner_type":"home_1","image":null,"link":null,"status":"active"}
     * @response 404 {"message":"Banner not found"}
     */
    /**
     * @OA\Get(
     *   path="/api/v1/banners/{id}",
     *   summary="Get banner",
     *   tags={"Banners"},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="OK",
     *
     *     @OA\JsonContent(type="object",
     *
     *       @OA\Property(property="id", type="integer"),
     *       @OA\Property(property="banner_type", type="string"),
     *       @OA\Property(property="image", type="string", nullable=true),
     *       @OA\Property(property="link", type="string", nullable=true),
     *       @OA\Property(property="status", type="string")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(int $id)
    {
        $banner = $this->bannerRepository->findBanner($id);
        if (! $banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        return response()->json($banner->only(['id', 'banner_type', 'image', 'link', 'status']));
    }

    /**
     * Create Banner
     * Create a new banner.
     *
     * Accepts multipart for `image`.
     *
     * @group Banners
     *
     * @authenticated
     *
     * @bodyParam banner_type string required One of home_1, home_2, home_3, my_order, deposit, withdrawal, rewards.
     * @bodyParam image file The banner image (jpeg/png/jpg/gif/svg), max 2MB.
     * @bodyParam link string The URL to open when tapped.
     * @bodyParam status string required active or inactive.
     *
     * @response 201 {"message":"Banner created","data":{"id":1}}
     * @response 422 {"message":"Validation error"}
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_type' => 'required|in:home_1,home_2,home_3,my_order,deposit,withdrawal,rewards',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first() ?? 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['banner_type', 'link', 'status']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners/images', 'public');
        }
        $banner = $this->bannerRepository->createBanner($data);

        return response()->json(['message' => 'Banner created', 'data' => ['id' => $banner->id]], 201);
    }

    /**
     * Update Banner
     * Update an existing banner.
     *
     * @group Banners
     *
     * @authenticated
     *
     * @bodyParam banner_type string One of home_1, home_2, home_3, my_order, deposit, withdrawal, rewards.
     * @bodyParam image file The banner image (jpeg/png/jpg/gif/svg), max 2MB.
     * @bodyParam link string The URL to open when tapped.
     * @bodyParam status string active or inactive.
     *
     * @response 200 {"message":"Banner updated"}
     * @response 404 {"message":"Banner not found"}
     */
    public function update(Request $request, int $id)
    {
        $banner = $this->bannerRepository->findBanner($id);
        if (! $banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'banner_type' => 'nullable|in:home_1,home_2,home_3,my_order,deposit,withdrawal,rewards',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first() ?? 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['banner_type', 'link', 'status']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners/images', 'public');
        }

        $updated = $this->bannerRepository->updateBanner($id, $data);

        return response()->json(['message' => $updated ? 'Banner updated' : 'No changes']);
    }

    /**
     * Delete Banner
     * Delete a banner by ID.
     *
     * @group Banners
     *
     * @authenticated
     *
     * @response 200 {"message":"Banner deleted"}
     * @response 404 {"message":"Banner not found"}
     */
    public function destroy(int $id)
    {
        $banner = $this->bannerRepository->findBanner($id);
        if (! $banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }
        $this->bannerRepository->deleteBanner($id);

        return response()->json(['message' => 'Banner deleted']);
    }
}
