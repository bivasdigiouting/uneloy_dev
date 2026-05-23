# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer Bearer {YOUR_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

You can retrieve your token by logging in via the <code>POST /api/v1/auth/login</code> endpoint. Include the token in the Authorization header as <code>Bearer {token}</code>.
