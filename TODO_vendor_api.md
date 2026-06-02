# TODO - Vendor APIs

## Implemented (this task)
- Added Vendor "New bill" tab APIs:
  - `GET /vendor/billing/new-bill/customers/search?q=`
  - `GET /vendor/billing/new-bill/purchased-products?customer_name=&from=&to=`

## Pending
- Verify endpoints with Postman (auth:sanctum vendor token required)
- Align response payload with vendor frontend (if needed)
- Optional: change search to use a real customer id (if customer table exists)

