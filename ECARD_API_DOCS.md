# ECard API Documentation

## Introduction
This documentation details the REST API endpoints for the ECard application, specifically for ECard users.

**Base URL**: `http://localhost/api/ecard`

## Authentication

### Login
Authenticates a user using User ID, Email, or Mobile Number and Password.

- **URL**: `/login`
- **Method**: `POST`
- **Headers**:
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
- **Body**:
  ```json
  {
    "login": "USER123", // Can be User ID, Email, or Mobile Number
    "password": "password123"
  }
  ```
- **Response (Success - 200)**:
  ```json
  {
    "message": "Login successful",
    "token": "1|laravel_sanctum_token...",
    "user": {
      "id": 1,
      "user_id": "USER123",
      "email_id": "user@example.com",
      "mobile_no": "9876543210",
      ...
    }
  }
  ```
- **Response (Error - 422)**:
  ```json
  {
    "message": "The given data was invalid.",
    "errors": {
      "login": [
        "The provided credentials are incorrect."
      ]
    }
  }
  ```

### Logout
Invalidates the current authentication token.

- **URL**: `/logout`
- **Method**: `POST`
- **Headers**:
  - `Authorization`: `Bearer <token>`
  - `Accept`: `application/json`
- **Response (Success - 200)**:
  ```json
  {
    "message": "Logged out successfully"
  }
  ```

## MPIN Management

### Update MPIN
Sets or updates the user's MPIN.

- **URL**: `/mpin/update`
- **Method**: `POST`
- **Headers**:
  - `Authorization`: `Bearer <token>`
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
- **Body**:
  ```json
  {
    "mpin": "123456" // 4 to 6 digits
  }
  ```
- **Response (Success - 200)**:
  ```json
  {
    "message": "MPIN updated successfully"
  }
  ```

### Verify MPIN
Verifies the user's MPIN.

- **URL**: `/mpin/verify`
- **Method**: `POST`
- **Headers**:
  - `Authorization`: `Bearer <token>`
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
- **Body**:
  ```json
  {
    "mpin": "123456"
  }
  ```
- **Response (Success - 200)**:
  ```json
  {
    "message": "MPIN verified successfully"
  }
  ```
- **Response (Error - 422)**:
  ```json
  {
    "message": "The given data was invalid.",
    "errors": {
      "mpin": [
        "Invalid MPIN."
      ]
    }
  }
  ```
