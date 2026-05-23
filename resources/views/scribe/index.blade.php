<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Uonely Solution Pvt. Ltd. - A Company that provide, infinite solutions. API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://ecardseva.uonely.com";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-ecard-login">
                                <a href="#authentication-POSTapi-ecard-login">ECard Login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-ecard-logout">
                                <a href="#authentication-POSTapi-ecard-logout">ECard Logout</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ecard-registration" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ecard-registration">
                    <a href="#ecard-registration">ECard Registration</a>
                </li>
                                    <ul id="tocify-subheader-ecard-registration" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ecard-registration-GETapi-ecard-registration-list">
                                <a href="#ecard-registration-GETapi-ecard-registration-list">Register New User</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ecard-registration-POSTapi-ecard-registration-create">
                                <a href="#ecard-registration-POSTapi-ecard-registration-create">Register New User</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-documentation">
                                <a href="#endpoints-GETapi-documentation">Handles the API request and renders the Swagger documentation view.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-oauth2-callback">
                                <a href="#endpoints-GETapi-oauth2-callback">Handles the OAuth2 callback and retrieves the required file for the redirect.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-mpin-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="mpin-management">
                    <a href="#mpin-management">MPIN Management</a>
                </li>
                                    <ul id="tocify-subheader-mpin-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="mpin-management-POSTapi-ecard-mpin-update">
                                <a href="#mpin-management-POSTapi-ecard-mpin-update">Update MPIN</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mpin-management-POSTapi-ecard-mpin-verify">
                                <a href="#mpin-management-POSTapi-ecard-mpin-verify">Verify MPIN</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-master-data" class="tocify-header">
                <li class="tocify-item level-1" data-unique="master-data">
                    <a href="#master-data">Master Data</a>
                </li>
                                    <ul id="tocify-subheader-master-data" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="master-data-GETapi-ecard-states">
                                <a href="#master-data-GETapi-ecard-states">Get States</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="master-data-GETapi-ecard-districts--state_id-">
                                <a href="#master-data-GETapi-ecard-districts--state_id-">Get Districts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="master-data-GETapi-ecard-cities--district_id-">
                                <a href="#master-data-GETapi-ecard-cities--district_id-">Get Cities</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-qr-code" class="tocify-header">
                <li class="tocify-item level-1" data-unique="qr-code">
                    <a href="#qr-code">QR Code</a>
                </li>
                                    <ul id="tocify-subheader-qr-code" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="qr-code-GETapi-ecard-qrcode-generate">
                                <a href="#qr-code-GETapi-ecard-qrcode-generate">Generate QR Code</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="qr-code-GETapi-ecard-qrcode-view">
                                <a href="#qr-code-GETapi-ecard-qrcode-view">View QR Code</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-user-profile" class="tocify-header">
                <li class="tocify-item level-1" data-unique="user-profile">
                    <a href="#user-profile">User Profile</a>
                </li>
                                    <ul id="tocify-subheader-user-profile" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="user-profile-GETapi-ecard-profile">
                                <a href="#user-profile-GETapi-ecard-profile">Get Profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="user-profile-POSTapi-ecard-profile-update">
                                <a href="#user-profile-POSTapi-ecard-profile-update">Update Profile</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-verification" class="tocify-header">
                <li class="tocify-item level-1" data-unique="verification">
                    <a href="#verification">Verification</a>
                </li>
                                    <ul id="tocify-subheader-verification" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="verification-POSTapi-ecard-verification-mobile-send">
                                <a href="#verification-POSTapi-ecard-verification-mobile-send">Send Mobile OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="verification-POSTapi-ecard-verification-mobile-verify">
                                <a href="#verification-POSTapi-ecard-verification-mobile-verify">Verify Mobile OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="verification-POSTapi-ecard-verification-email-send">
                                <a href="#verification-POSTapi-ecard-verification-email-send">Send Email OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="verification-POSTapi-ecard-verification-email-verify">
                                <a href="#verification-POSTapi-ecard-verification-email-verify">Verify Email OTP</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-wallet" class="tocify-header">
                <li class="tocify-item level-1" data-unique="wallet">
                    <a href="#wallet">Wallet</a>
                </li>
                                    <ul id="tocify-subheader-wallet" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="wallet-GETapi-ecard-wallet-balance">
                                <a href="#wallet-GETapi-ecard-wallet-balance">Get Wallet Balance</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="wallet-GETapi-ecard-wallet-transactions">
                                <a href="#wallet-GETapi-ecard-wallet-transactions">Get Wallet Transactions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="wallet-POSTapi-ecard-wallet-add-money">
                                <a href="#wallet-POSTapi-ecard-wallet-add-money">Add Money</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="wallet-GETapi-ecard-wallet-verify-payment">
                                <a href="#wallet-GETapi-ecard-wallet-verify-payment">Verify Payment</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: February 3, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>UOnly API provides comprehensive endpoints for user authentication and product management with Laravel Sanctum security.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer Bearer {YOUR_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>You can retrieve your token by logging in via the <code>POST /api/v1/auth/login</code> endpoint. Include the token in the Authorization header as <code>Bearer {token}</code>.</p>

        <h1 id="authentication">Authentication</h1>

    

                                <h2 id="authentication-POSTapi-ecard-login">ECard Login</h2>

<p>
</p>

<p>Authenticate an ECard user using User ID, Email, or Mobile Number.</p>

<span id="example-requests-POSTapi-ecard-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"login\": \"USER123\",
    \"password\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "login": "USER123",
    "password": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-login">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Login successful&quot;,
    &quot;token&quot;: &quot;1|laravel_sanctum_token...&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;user_level&quot;: &quot;state_level&quot;,
        &quot;department_level&quot;: &quot;state_level&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;login&quot;: [
            &quot;The provided credentials are incorrect.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-login" data-method="POST"
      data-path="api/ecard/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-login"
                    onclick="tryItOut('POSTapi-ecard-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-login"
                    onclick="cancelTryOut('POSTapi-ecard-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>login</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="login"                data-endpoint="POSTapi-ecard-login"
               value="USER123"
               data-component="body">
    <br>
<p>The User ID, Email ID, or Mobile Number. Example: <code>USER123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-ecard-login"
               value="password123"
               data-component="body">
    <br>
<p>The password. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-ecard-logout">ECard Logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Revoke the current user's access token.</p>

<span id="example-requests-POSTapi-ecard-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/logout" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/logout"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Logged out successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-logout" data-method="POST"
      data-path="api/ecard/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-logout"
                    onclick="tryItOut('POSTapi-ecard-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-logout"
                    onclick="cancelTryOut('POSTapi-ecard-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-logout"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="ecard-registration">ECard Registration</h1>

    

                                <h2 id="ecard-registration-GETapi-ecard-registration-list">Register New User</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Create a new ECard Registration or Member Registration.</p>

<span id="example-requests-GETapi-ecard-registration-list">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/registration/list" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "department_level=state_level"\
    --form "business_category=Private limited"\
    --form "first_name=John"\
    --form "last_name=Doe"\
    --form "state_id=1"\
    --form "district_id=1"\
    --form "city_id=1"\
    --form "mobile_no=9876543210"\
    --form "email_id=john@example.com"\
    --form "profile_image=@C:\Users\patra\AppData\Local\Temp\phpAC33.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/registration/list"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('department_level', 'state_level');
body.append('business_category', 'Private limited');
body.append('first_name', 'John');
body.append('last_name', 'Doe');
body.append('state_id', '1');
body.append('district_id', '1');
body.append('city_id', '1');
body.append('mobile_no', '9876543210');
body.append('email_id', 'john@example.com');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);

fetch(url, {
    method: "GET",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-registration-list">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-registration-list" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-registration-list"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-registration-list"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-registration-list" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-registration-list">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-registration-list" data-method="GET"
      data-path="api/ecard/registration/list"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-registration-list', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-registration-list"
                    onclick="tryItOut('GETapi-ecard-registration-list');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-registration-list"
                    onclick="cancelTryOut('GETapi-ecard-registration-list');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-registration-list"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/registration/list</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-registration-list"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-registration-list"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-registration-list"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>department_level</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="department_level"                data-endpoint="GETapi-ecard-registration-list"
               value="state_level"
               data-component="body">
    <br>
<p>nullable The current department level. Example: <code>state_level</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_category"                data-endpoint="GETapi-ecard-registration-list"
               value="Private limited"
               data-component="body">
    <br>
<p>nullable The business category. Example: <code>Private limited</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="GETapi-ecard-registration-list"
               value="John"
               data-component="body">
    <br>
<p>nullable First name. Example: <code>John</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="GETapi-ecard-registration-list"
               value="Doe"
               data-component="body">
    <br>
<p>nullable Last name. Example: <code>Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>state_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="state_id"                data-endpoint="GETapi-ecard-registration-list"
               value="1"
               data-component="body">
    <br>
<p>nullable State ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>district_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="district_id"                data-endpoint="GETapi-ecard-registration-list"
               value="1"
               data-component="body">
    <br>
<p>nullable District ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city_id</code></b>&nbsp;&nbsp;
<small>mixed</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city_id"                data-endpoint="GETapi-ecard-registration-list"
               value="1"
               data-component="body">
    <br>
<p>nullable City ID or '<strong>other</strong>'. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mobile_no"                data-endpoint="GETapi-ecard-registration-list"
               value="9876543210"
               data-component="body">
    <br>
<p>nullable Mobile number. Example: <code>9876543210</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email_id"                data-endpoint="GETapi-ecard-registration-list"
               value="john@example.com"
               data-component="body">
    <br>
<p>nullable Email address. Example: <code>john@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="GETapi-ecard-registration-list"
               value=""
               data-component="body">
    <br>
<p>nullable Profile image. Example: <code>C:\Users\patra\AppData\Local\Temp\phpAC33.tmp</code></p>
        </div>
        </form>

                    <h2 id="ecard-registration-POSTapi-ecard-registration-create">Register New User</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Create a new ECard Registration or Member Registration.</p>

<span id="example-requests-POSTapi-ecard-registration-create">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/registration/create" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "department_level=state_level"\
    --form "business_category=Private limited"\
    --form "first_name=John"\
    --form "middle_name=consequatur"\
    --form "last_name=Doe"\
    --form "father_name=consequatur"\
    --form "mother_name=consequatur"\
    --form "blood_group=A+"\
    --form "date_of_birth=1990-01-01"\
    --form "gender=Male"\
    --form "marital_status=Single"\
    --form "current_address=consequatur"\
    --form "permanent_address=consequatur"\
    --form "nationality=INDIA"\
    --form "state_id=1"\
    --form "district_id=1"\
    --form "city_id=1"\
    --form "city_other=consequatur"\
    --form "pin_code=123456"\
    --form "mobile_no=9876543210"\
    --form "phone_no=consequatur"\
    --form "email_id=john@example.com"\
    --form "gmail_id=consequatur"\
    --form "live_location_map=consequatur"\
    --form "ifsc_code=consequatur"\
    --form "bank_name=consequatur"\
    --form "branch_name=consequatur"\
    --form "account_no=consequatur"\
    --form "pan_no=consequatur"\
    --form "aadhaar_no=consequatur"\
    --form "last_qualification=consequatur"\
    --form "work_type=consequatur"\
    --form "work_experience=consequatur"\
    --form "agree_terms="\
    --form "customer_user_type=consequatur"\
    --form "first_recharge_plan_id=17"\
    --form "business_name=consequatur"\
    --form "business_mobile=consequatur"\
    --form "business_whatsapp=consequatur"\
    --form "business_gmail=consequatur"\
    --form "business_address=consequatur"\
    --form "business_gst=consequatur"\
    --form "business_upi=consequatur"\
    --form "business_location_map=consequatur"\
    --form "otp_required="\
    --form "otp_code=consequatur"\
    --form "area=consequatur"\
    --form "panchayat=consequatur"\
    --form "village_name=consequatur"\
    --form "panchayat_other=consequatur"\
    --form "village_other=consequatur"\
    --form "municipality=consequatur"\
    --form "municipality_id=17"\
    --form "municipality_other=consequatur"\
    --form "ward_no=consequatur"\
    --form "ward_other=consequatur"\
    --form "profile_image=@C:\Users\patra\AppData\Local\Temp\phpAC54.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/registration/create"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('department_level', 'state_level');
body.append('business_category', 'Private limited');
body.append('first_name', 'John');
body.append('middle_name', 'consequatur');
body.append('last_name', 'Doe');
body.append('father_name', 'consequatur');
body.append('mother_name', 'consequatur');
body.append('blood_group', 'A+');
body.append('date_of_birth', '1990-01-01');
body.append('gender', 'Male');
body.append('marital_status', 'Single');
body.append('current_address', 'consequatur');
body.append('permanent_address', 'consequatur');
body.append('nationality', 'INDIA');
body.append('state_id', '1');
body.append('district_id', '1');
body.append('city_id', '1');
body.append('city_other', 'consequatur');
body.append('pin_code', '123456');
body.append('mobile_no', '9876543210');
body.append('phone_no', 'consequatur');
body.append('email_id', 'john@example.com');
body.append('gmail_id', 'consequatur');
body.append('live_location_map', 'consequatur');
body.append('ifsc_code', 'consequatur');
body.append('bank_name', 'consequatur');
body.append('branch_name', 'consequatur');
body.append('account_no', 'consequatur');
body.append('pan_no', 'consequatur');
body.append('aadhaar_no', 'consequatur');
body.append('last_qualification', 'consequatur');
body.append('work_type', 'consequatur');
body.append('work_experience', 'consequatur');
body.append('agree_terms', '');
body.append('customer_user_type', 'consequatur');
body.append('first_recharge_plan_id', '17');
body.append('business_name', 'consequatur');
body.append('business_mobile', 'consequatur');
body.append('business_whatsapp', 'consequatur');
body.append('business_gmail', 'consequatur');
body.append('business_address', 'consequatur');
body.append('business_gst', 'consequatur');
body.append('business_upi', 'consequatur');
body.append('business_location_map', 'consequatur');
body.append('otp_required', '');
body.append('otp_code', 'consequatur');
body.append('area', 'consequatur');
body.append('panchayat', 'consequatur');
body.append('village_name', 'consequatur');
body.append('panchayat_other', 'consequatur');
body.append('village_other', 'consequatur');
body.append('municipality', 'consequatur');
body.append('municipality_id', '17');
body.append('municipality_other', 'consequatur');
body.append('ward_no', 'consequatur');
body.append('ward_other', 'consequatur');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-registration-create">
</span>
<span id="execution-results-POSTapi-ecard-registration-create" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-registration-create"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-registration-create"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-registration-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-registration-create">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-registration-create" data-method="POST"
      data-path="api/ecard/registration/create"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-registration-create', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-registration-create"
                    onclick="tryItOut('POSTapi-ecard-registration-create');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-registration-create"
                    onclick="cancelTryOut('POSTapi-ecard-registration-create');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-registration-create"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/registration/create</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-registration-create"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-registration-create"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-registration-create"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>department_level</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="department_level"                data-endpoint="POSTapi-ecard-registration-create"
               value="state_level"
               data-component="body">
    <br>
<p>nullable The current department level. Example: <code>state_level</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_category"                data-endpoint="POSTapi-ecard-registration-create"
               value="Private limited"
               data-component="body">
    <br>
<p>nullable The business category. Example: <code>Private limited</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="John"
               data-component="body">
    <br>
<p>nullable First name. Example: <code>John</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Middle name. Example: Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="Doe"
               data-component="body">
    <br>
<p>nullable Last name. Example: <code>Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>father_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="father_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Father's name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mother_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mother_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Mother's name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>blood_group</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="blood_group"                data-endpoint="POSTapi-ecard-registration-create"
               value="A+"
               data-component="body">
    <br>
<p>nullable Blood group. Example: <code>A+</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_of_birth</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="date_of_birth"                data-endpoint="POSTapi-ecard-registration-create"
               value="1990-01-01"
               data-component="body">
    <br>
<p>nullable Date of birth. Example: <code>1990-01-01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gender</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gender"                data-endpoint="POSTapi-ecard-registration-create"
               value="Male"
               data-component="body">
    <br>
<p>nullable Gender. Example: <code>Male</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>marital_status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="marital_status"                data-endpoint="POSTapi-ecard-registration-create"
               value="Single"
               data-component="body">
    <br>
<p>nullable Marital status. Example: <code>Single</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="current_address"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Current address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>permanent_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="permanent_address"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Permanent address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nationality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="nationality"                data-endpoint="POSTapi-ecard-registration-create"
               value="INDIA"
               data-component="body">
    <br>
<p>nullable Nationality. Example: <code>INDIA</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>state_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="state_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="1"
               data-component="body">
    <br>
<p>nullable State ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>district_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="district_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="1"
               data-component="body">
    <br>
<p>nullable District ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city_id</code></b>&nbsp;&nbsp;
<small>mixed</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="1"
               data-component="body">
    <br>
<p>nullable City ID or '<strong>other</strong>'. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city_other</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city_other"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Other city name if city_id is <strong>other</strong>. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pin_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="pin_code"                data-endpoint="POSTapi-ecard-registration-create"
               value="123456"
               data-component="body">
    <br>
<p>nullable Pin code. Example: <code>123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mobile_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="9876543210"
               data-component="body">
    <br>
<p>nullable Mobile number. Example: <code>9876543210</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="phone_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Phone number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="john@example.com"
               data-component="body">
    <br>
<p>nullable Email address. Example: <code>john@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gmail_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gmail_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Gmail address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>live_location_map</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="live_location_map"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Live location map URL. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ifsc_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ifsc_code"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Bank IFSC code. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>bank_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="bank_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Bank name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>branch_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="branch_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Branch name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>account_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="account_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Account number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pan_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="pan_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable PAN number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>aadhaar_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="aadhaar_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Aadhaar number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_qualification</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_qualification"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Last qualification. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>work_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="work_type"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Work type. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>work_experience</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="work_experience"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Work experience. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>agree_terms</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ecard-registration-create" style="display: none">
            <input type="radio" name="agree_terms"
                   value="true"
                   data-endpoint="POSTapi-ecard-registration-create"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ecard-registration-create" style="display: none">
            <input type="radio" name="agree_terms"
                   value="false"
                   data-endpoint="POSTapi-ecard-registration-create"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>nullable Agree to terms. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-ecard-registration-create"
               value=""
               data-component="body">
    <br>
<p>nullable Profile image. Example: <code>C:\Users\patra\AppData\Local\Temp\phpAC54.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_user_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="customer_user_type"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Member user type (free/paid). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_recharge_plan_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="first_recharge_plan_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="17"
               data-component="body">
    <br>
<p>nullable First recharge plan ID. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_mobile"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business mobile. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_whatsapp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_whatsapp"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business whatsapp. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_gmail</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_gmail"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business gmail. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_address"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_gst</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_gst"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business GST. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_upi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_upi"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business UPI. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>business_location_map</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="business_location_map"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Business location map. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp_required</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ecard-registration-create" style="display: none">
            <input type="radio" name="otp_required"
                   value="true"
                   data-endpoint="POSTapi-ecard-registration-create"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ecard-registration-create" style="display: none">
            <input type="radio" name="otp_required"
                   value="false"
                   data-endpoint="POSTapi-ecard-registration-create"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>nullable OTP required. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="otp_code"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable OTP code. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="area"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Area (Village_area/Municipality_area). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>panchayat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="panchayat"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Panchayat name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>village_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="village_name"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Village name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>panchayat_other</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="panchayat_other"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Other panchayat name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>village_other</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="village_other"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Other village name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>municipality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="municipality"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Municipality name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>municipality_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="municipality_id"                data-endpoint="POSTapi-ecard-registration-create"
               value="17"
               data-component="body">
    <br>
<p>nullable Municipality ID. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>municipality_other</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="municipality_other"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Other municipality name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ward_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ward_no"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Ward number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ward_other</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ward_other"                data-endpoint="POSTapi-ecard-registration-create"
               value="consequatur"
               data-component="body">
    <br>
<p>nullable Other ward number. Example: <code>consequatur</code></p>
        </div>
        </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-documentation">Handles the API request and renders the Swagger documentation view.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-documentation">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/documentation" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/documentation"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-documentation">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-documentation" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-documentation"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-documentation"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-documentation" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-documentation">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-documentation" data-method="GET"
      data-path="api/documentation"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-documentation', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-documentation"
                    onclick="tryItOut('GETapi-documentation');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-documentation"
                    onclick="cancelTryOut('GETapi-documentation');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-documentation"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/documentation</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-documentation"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-documentation"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-documentation"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-oauth2-callback">Handles the OAuth2 callback and retrieves the required file for the redirect.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-oauth2-callback">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/oauth2-callback" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/oauth2-callback"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-oauth2-callback">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">content-type: text/html; charset=UTF-8
cache-control: no-cache, private
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&lt;!doctype html&gt;
&lt;html lang=&quot;en-US&quot;&gt;
&lt;body&gt;
&lt;/body&gt;
&lt;/html&gt;
&lt;script src=&quot;oauth2-redirect.js&quot;&gt;&lt;/script&gt;</code>
 </pre>
    </span>
<span id="execution-results-GETapi-oauth2-callback" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-oauth2-callback"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-oauth2-callback"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-oauth2-callback" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-oauth2-callback">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-oauth2-callback" data-method="GET"
      data-path="api/oauth2-callback"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-oauth2-callback', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-oauth2-callback"
                    onclick="tryItOut('GETapi-oauth2-callback');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-oauth2-callback"
                    onclick="cancelTryOut('GETapi-oauth2-callback');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-oauth2-callback"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/oauth2-callback</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-oauth2-callback"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-oauth2-callback"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-oauth2-callback"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/user" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/user"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-user"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="mpin-management">MPIN Management</h1>

    

                                <h2 id="mpin-management-POSTapi-ecard-mpin-update">Update MPIN</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update or set the MPIN for the authenticated user.</p>

<span id="example-requests-POSTapi-ecard-mpin-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/mpin/update" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mpin\": \"1234\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/mpin/update"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mpin": "1234"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-mpin-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;MPIN updated successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;mpin&quot;: [
            &quot;The mpin must be between 4 and 6 digits.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-mpin-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-mpin-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-mpin-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-mpin-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-mpin-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-mpin-update" data-method="POST"
      data-path="api/ecard/mpin/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-mpin-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-mpin-update"
                    onclick="tryItOut('POSTapi-ecard-mpin-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-mpin-update"
                    onclick="cancelTryOut('POSTapi-ecard-mpin-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-mpin-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/mpin/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-mpin-update"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-mpin-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-mpin-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mpin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mpin"                data-endpoint="POSTapi-ecard-mpin-update"
               value="1234"
               data-component="body">
    <br>
<p>The new MPIN (4-6 digits). Example: <code>1234</code></p>
        </div>
        </form>

                    <h2 id="mpin-management-POSTapi-ecard-mpin-verify">Verify MPIN</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Verify the MPIN for the authenticated user.</p>

<span id="example-requests-POSTapi-ecard-mpin-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/mpin/verify" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mpin\": \"1234\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/mpin/verify"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mpin": "1234"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-mpin-verify">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;MPIN verified successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;mpin&quot;: [
            &quot;Invalid MPIN.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-mpin-verify" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-mpin-verify"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-mpin-verify"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-mpin-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-mpin-verify">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-mpin-verify" data-method="POST"
      data-path="api/ecard/mpin/verify"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-mpin-verify', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-mpin-verify"
                    onclick="tryItOut('POSTapi-ecard-mpin-verify');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-mpin-verify"
                    onclick="cancelTryOut('POSTapi-ecard-mpin-verify');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-mpin-verify"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/mpin/verify</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-mpin-verify"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-mpin-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-mpin-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mpin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mpin"                data-endpoint="POSTapi-ecard-mpin-verify"
               value="1234"
               data-component="body">
    <br>
<p>The MPIN to verify. Example: <code>1234</code></p>
        </div>
        </form>

                <h1 id="master-data">Master Data</h1>

    

                                <h2 id="master-data-GETapi-ecard-states">Get States</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Fetch list of all active states.</p>

<span id="example-requests-GETapi-ecard-states">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/states" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/states"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-states">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;states&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;state_name&quot;: &quot;Maharashtra&quot;,
            &quot;status&quot;: &quot;active&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;state_name&quot;: &quot;Karnataka&quot;,
            &quot;status&quot;: &quot;active&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-states" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-states"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-states"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-states" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-states">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-states" data-method="GET"
      data-path="api/ecard/states"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-states', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-states"
                    onclick="tryItOut('GETapi-ecard-states');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-states"
                    onclick="cancelTryOut('GETapi-ecard-states');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-states"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/states</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-states"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-states"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-states"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="master-data-GETapi-ecard-districts--state_id-">Get Districts</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Fetch list of districts for a given state.</p>

<span id="example-requests-GETapi-ecard-districts--state_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/districts/17" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/districts/17"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-districts--state_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;districts&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;district_name&quot;: &quot;Pune&quot;,
            &quot;state_id&quot;: 1,
            &quot;status&quot;: &quot;active&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-districts--state_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-districts--state_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-districts--state_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-districts--state_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-districts--state_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-districts--state_id-" data-method="GET"
      data-path="api/ecard/districts/{state_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-districts--state_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-districts--state_id-"
                    onclick="tryItOut('GETapi-ecard-districts--state_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-districts--state_id-"
                    onclick="cancelTryOut('GETapi-ecard-districts--state_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-districts--state_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/districts/{state_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-districts--state_id-"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-districts--state_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-districts--state_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>state_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="state_id"                data-endpoint="GETapi-ecard-districts--state_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the state. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="master-data-GETapi-ecard-cities--district_id-">Get Cities</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Fetch list of cities for a given district.</p>

<span id="example-requests-GETapi-ecard-cities--district_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/cities/17" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/cities/17"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-cities--district_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;cities&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;city_name&quot;: &quot;Pune City&quot;,
            &quot;district_id&quot;: 1,
            &quot;status&quot;: &quot;active&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-cities--district_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-cities--district_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-cities--district_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-cities--district_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-cities--district_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-cities--district_id-" data-method="GET"
      data-path="api/ecard/cities/{district_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-cities--district_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-cities--district_id-"
                    onclick="tryItOut('GETapi-ecard-cities--district_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-cities--district_id-"
                    onclick="cancelTryOut('GETapi-ecard-cities--district_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-cities--district_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/cities/{district_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-cities--district_id-"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-cities--district_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-cities--district_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>district_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="district_id"                data-endpoint="GETapi-ecard-cities--district_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the district. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="qr-code">QR Code</h1>

    <p>APIs for generating and viewing QR codes for ECard users.</p>

                                <h2 id="qr-code-GETapi-ecard-qrcode-generate">Generate QR Code</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Generate a QR code for the authenticated user based on their User ID.
If the QR code already exists in the database, it returns the existing URL.
Otherwise, it generates a new one, saves it, and updates the database.</p>

<span id="example-requests-GETapi-ecard-qrcode-generate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/qrcode/generate" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/qrcode/generate"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-qrcode-generate">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;QR Code generated successfully&quot;,
    &quot;url&quot;: &quot;http://localhost/storage/qrcodes/user_1.png&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-qrcode-generate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-qrcode-generate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-qrcode-generate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-qrcode-generate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-qrcode-generate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-qrcode-generate" data-method="GET"
      data-path="api/ecard/qrcode/generate"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-qrcode-generate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-qrcode-generate"
                    onclick="tryItOut('GETapi-ecard-qrcode-generate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-qrcode-generate"
                    onclick="cancelTryOut('GETapi-ecard-qrcode-generate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-qrcode-generate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/qrcode/generate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-qrcode-generate"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-qrcode-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-qrcode-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="qr-code-GETapi-ecard-qrcode-view">View QR Code</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retrieve the generated QR code image for the authenticated user.
Uses the path stored in the database if available.</p>

<span id="example-requests-GETapi-ecard-qrcode-view">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/qrcode/view" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/qrcode/view"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-qrcode-view">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">&lt;&lt;binary data&gt;&gt;</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;QR Code not found. Please generate it first.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-qrcode-view" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-qrcode-view"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-qrcode-view"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-qrcode-view" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-qrcode-view">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-qrcode-view" data-method="GET"
      data-path="api/ecard/qrcode/view"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-qrcode-view', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-qrcode-view"
                    onclick="tryItOut('GETapi-ecard-qrcode-view');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-qrcode-view"
                    onclick="cancelTryOut('GETapi-ecard-qrcode-view');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-qrcode-view"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/qrcode/view</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-qrcode-view"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-qrcode-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-qrcode-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="user-profile">User Profile</h1>

    

                                <h2 id="user-profile-GETapi-ecard-profile">Get Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Fetch the authenticated user's profile details.</p>

<span id="example-requests-GETapi-ecard-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/profile" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/profile"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-profile">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Profile fetched successfully&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;first_name&quot;: &quot;John&quot;,
        &quot;last_name&quot;: &quot;Doe&quot;,
        &quot;mobile_no&quot;: &quot;9876543210&quot;,
        &quot;email_id&quot;: &quot;john@example.com&quot;,
        &quot;profile_image_url&quot;: &quot;http://localhost/storage/profile_images/abc.jpg&quot;,
        &quot;state&quot;: &quot;1&quot;,
        &quot;district&quot;: &quot;2&quot;,
        &quot;city&quot;: &quot;3&quot;,
        &quot;whatsapp_no&quot;: &quot;9876543210&quot;,
        &quot;user_level&quot;: &quot;state_level&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-profile" data-method="GET"
      data-path="api/ecard/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-profile"
                    onclick="tryItOut('GETapi-ecard-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-profile"
                    onclick="cancelTryOut('GETapi-ecard-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-profile"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="user-profile-POSTapi-ecard-profile-update">Update Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update the user's profile information including profile image.</p>

<span id="example-requests-POSTapi-ecard-profile-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/profile/update" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "first_name=consequatur"\
    --form "middle_name=consequatur"\
    --form "last_name=consequatur"\
    --form "father_name=consequatur"\
    --form "mother_name=consequatur"\
    --form "blood_group=consequatur"\
    --form "date_of_birth=consequatur"\
    --form "gender=consequatur"\
    --form "marital_status=consequatur"\
    --form "mobile_no=consequatur"\
    --form "whatsapp_no=consequatur"\
    --form "email_id=consequatur"\
    --form "gmail_id=yjpxmqesedyghenqcopwv"\
    --form "current_address=consequatur"\
    --form "permanent_address=consequatur"\
    --form "state=consequatur"\
    --form "district=consequatur"\
    --form "city=consequatur"\
    --form "pin_code=consequatur"\
    --form "last_qualification=consequatur"\
    --form "work_type=consequatur"\
    --form "work_experience=consequatur"\
    --form "profile_image=@C:\Users\patra\AppData\Local\Temp\phpABC5.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/profile/update"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('first_name', 'consequatur');
body.append('middle_name', 'consequatur');
body.append('last_name', 'consequatur');
body.append('father_name', 'consequatur');
body.append('mother_name', 'consequatur');
body.append('blood_group', 'consequatur');
body.append('date_of_birth', 'consequatur');
body.append('gender', 'consequatur');
body.append('marital_status', 'consequatur');
body.append('mobile_no', 'consequatur');
body.append('whatsapp_no', 'consequatur');
body.append('email_id', 'consequatur');
body.append('gmail_id', 'yjpxmqesedyghenqcopwv');
body.append('current_address', 'consequatur');
body.append('permanent_address', 'consequatur');
body.append('state', 'consequatur');
body.append('district', 'consequatur');
body.append('city', 'consequatur');
body.append('pin_code', 'consequatur');
body.append('last_qualification', 'consequatur');
body.append('work_type', 'consequatur');
body.append('work_experience', 'consequatur');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-profile-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Profile updated successfully&quot;,
    &quot;user&quot;: {
        &quot;first_name&quot;: &quot;John&quot;,
        &quot;last_name&quot;: &quot;Doe&quot;,
        &quot;profile_image&quot;: &quot;profile_images/xyz.jpg&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-profile-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-profile-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-profile-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-profile-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-profile-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-profile-update" data-method="POST"
      data-path="api/ecard/profile/update"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-profile-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-profile-update"
                    onclick="tryItOut('POSTapi-ecard-profile-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-profile-update"
                    onclick="cancelTryOut('POSTapi-ecard-profile-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-profile-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/profile/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-profile-update"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-profile-update"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-profile-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>First Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>middle_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="middle_name"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Middle Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Last Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>father_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="father_name"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Father's Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mother_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mother_name"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Mother's Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>blood_group</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="blood_group"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Blood Group (e.g., A+, B-). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>date_of_birth</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="date_of_birth"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Date of Birth (YYYY-MM-DD). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gender</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gender"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Gender (Male, Female, Other). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>marital_status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="marital_status"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Marital Status (Single, Married, Divorced, Widowed). Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile_no"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Mobile Number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>whatsapp_no</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="whatsapp_no"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>WhatsApp Number. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email_id"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Email Address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gmail_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="gmail_id"                data-endpoint="POSTapi-ecard-profile-update"
               value="yjpxmqesedyghenqcopwv"
               data-component="body">
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters. Example: <code>yjpxmqesedyghenqcopwv</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="current_address"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Current Address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>permanent_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="permanent_address"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Permanent Address. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="state"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>State ID or Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>district</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="district"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>District ID or Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>City ID or Name. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pin_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="pin_code"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Pin Code. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_qualification</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_qualification"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Last Qualification. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>work_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="work_type"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Work Type. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>work_experience</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="work_experience"                data-endpoint="POSTapi-ecard-profile-update"
               value="consequatur"
               data-component="body">
    <br>
<p>Work Experience. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-ecard-profile-update"
               value=""
               data-component="body">
    <br>
<p>The profile picture (jpeg, png, jpg, gif, max 2MB). Example: <code>C:\Users\patra\AppData\Local\Temp\phpABC5.tmp</code></p>
        </div>
        </form>

                <h1 id="verification">Verification</h1>

    

                                <h2 id="verification-POSTapi-ecard-verification-mobile-send">Send Mobile OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send an OTP to the user's registered mobile number for verification.</p>

<span id="example-requests-POSTapi-ecard-verification-mobile-send">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/verification/mobile/send" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/verification/mobile/send"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-verification-mobile-send">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;OTP sent successfully to registered mobile number.&quot;,
    &quot;dev_otp&quot;: 123456
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-verification-mobile-send" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-verification-mobile-send"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-verification-mobile-send"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-verification-mobile-send" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-verification-mobile-send">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-verification-mobile-send" data-method="POST"
      data-path="api/ecard/verification/mobile/send"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-verification-mobile-send', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-verification-mobile-send"
                    onclick="tryItOut('POSTapi-ecard-verification-mobile-send');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-verification-mobile-send"
                    onclick="cancelTryOut('POSTapi-ecard-verification-mobile-send');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-verification-mobile-send"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/verification/mobile/send</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-verification-mobile-send"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-verification-mobile-send"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-verification-mobile-send"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="verification-POSTapi-ecard-verification-mobile-verify">Verify Mobile OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Verify the OTP sent to the mobile number.</p>

<span id="example-requests-POSTapi-ecard-verification-mobile-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/verification/mobile/verify" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"otp\": \"123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/verification/mobile/verify"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "otp": "123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-verification-mobile-verify">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Mobile number verified successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;otp&quot;: [
            &quot;Invalid or expired OTP.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-verification-mobile-verify" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-verification-mobile-verify"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-verification-mobile-verify"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-verification-mobile-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-verification-mobile-verify">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-verification-mobile-verify" data-method="POST"
      data-path="api/ecard/verification/mobile/verify"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-verification-mobile-verify', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-verification-mobile-verify"
                    onclick="tryItOut('POSTapi-ecard-verification-mobile-verify');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-verification-mobile-verify"
                    onclick="cancelTryOut('POSTapi-ecard-verification-mobile-verify');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-verification-mobile-verify"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/verification/mobile/verify</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-verification-mobile-verify"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-verification-mobile-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-verification-mobile-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-ecard-verification-mobile-verify"
               value="123456"
               data-component="body">
    <br>
<p>The 6-digit OTP. Example: <code>123456</code></p>
        </div>
        </form>

                    <h2 id="verification-POSTapi-ecard-verification-email-send">Send Email OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send an OTP to the user's registered email address for verification.</p>

<span id="example-requests-POSTapi-ecard-verification-email-send">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/verification/email/send" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/verification/email/send"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-verification-email-send">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;OTP sent successfully to registered email address.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No email address found for this user.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-verification-email-send" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-verification-email-send"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-verification-email-send"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-verification-email-send" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-verification-email-send">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-verification-email-send" data-method="POST"
      data-path="api/ecard/verification/email/send"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-verification-email-send', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-verification-email-send"
                    onclick="tryItOut('POSTapi-ecard-verification-email-send');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-verification-email-send"
                    onclick="cancelTryOut('POSTapi-ecard-verification-email-send');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-verification-email-send"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/verification/email/send</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-verification-email-send"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-verification-email-send"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-verification-email-send"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="verification-POSTapi-ecard-verification-email-verify">Verify Email OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Verify the OTP sent to the email address.</p>

<span id="example-requests-POSTapi-ecard-verification-email-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/verification/email/verify" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"otp\": \"123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/verification/email/verify"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "otp": "123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-verification-email-verify">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Email address verified successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;otp&quot;: [
            &quot;Invalid or expired OTP.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-ecard-verification-email-verify" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-verification-email-verify"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-verification-email-verify"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-verification-email-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-verification-email-verify">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-verification-email-verify" data-method="POST"
      data-path="api/ecard/verification/email/verify"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-verification-email-verify', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-verification-email-verify"
                    onclick="tryItOut('POSTapi-ecard-verification-email-verify');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-verification-email-verify"
                    onclick="cancelTryOut('POSTapi-ecard-verification-email-verify');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-verification-email-verify"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/verification/email/verify</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-verification-email-verify"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-verification-email-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-verification-email-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-ecard-verification-email-verify"
               value="123456"
               data-component="body">
    <br>
<p>The 6-digit OTP. Example: <code>123456</code></p>
        </div>
        </form>

                <h1 id="wallet">Wallet</h1>

    

                                <h2 id="wallet-GETapi-ecard-wallet-balance">Get Wallet Balance</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the current wallet balance of the authenticated user.</p>

<span id="example-requests-GETapi-ecard-wallet-balance">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/wallet/balance" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/wallet/balance"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-wallet-balance">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-wallet-balance" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-wallet-balance"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-wallet-balance"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-wallet-balance" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-wallet-balance">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-wallet-balance" data-method="GET"
      data-path="api/ecard/wallet/balance"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-wallet-balance', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-wallet-balance"
                    onclick="tryItOut('GETapi-ecard-wallet-balance');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-wallet-balance"
                    onclick="cancelTryOut('GETapi-ecard-wallet-balance');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-wallet-balance"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/wallet/balance</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-wallet-balance"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-wallet-balance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-wallet-balance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="wallet-GETapi-ecard-wallet-transactions">Get Wallet Transactions</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of wallet transactions.</p>

<span id="example-requests-GETapi-ecard-wallet-transactions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/wallet/transactions" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/wallet/transactions"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-wallet-transactions">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-wallet-transactions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-wallet-transactions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-wallet-transactions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-wallet-transactions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-wallet-transactions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-wallet-transactions" data-method="GET"
      data-path="api/ecard/wallet/transactions"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-wallet-transactions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-wallet-transactions"
                    onclick="tryItOut('GETapi-ecard-wallet-transactions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-wallet-transactions"
                    onclick="cancelTryOut('GETapi-ecard-wallet-transactions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-wallet-transactions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/wallet/transactions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-wallet-transactions"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-wallet-transactions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-wallet-transactions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="wallet-POSTapi-ecard-wallet-add-money">Add Money</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Initiates a wallet top-up transaction using the active payment gateway.</p>

<span id="example-requests-POSTapi-ecard-wallet-add-money">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://ecardseva.uonely.com/api/ecard/wallet/add-money" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"amount\": 100
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/wallet/add-money"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "amount": 100
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ecard-wallet-add-money">
</span>
<span id="execution-results-POSTapi-ecard-wallet-add-money" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ecard-wallet-add-money"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ecard-wallet-add-money"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ecard-wallet-add-money" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ecard-wallet-add-money">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ecard-wallet-add-money" data-method="POST"
      data-path="api/ecard/wallet/add-money"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ecard-wallet-add-money', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ecard-wallet-add-money"
                    onclick="tryItOut('POSTapi-ecard-wallet-add-money');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ecard-wallet-add-money"
                    onclick="cancelTryOut('POSTapi-ecard-wallet-add-money');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ecard-wallet-add-money"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/wallet/add-money</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ecard-wallet-add-money"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ecard-wallet-add-money"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-ecard-wallet-add-money"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-ecard-wallet-add-money"
               value="100"
               data-component="body">
    <br>
<p>The amount to add (e.g., 100.00). Example: <code>100</code></p>
        </div>
        </form>

                    <h2 id="wallet-GETapi-ecard-wallet-verify-payment">Verify Payment</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Verifies the payment status with the gateway and updates the wallet balance.</p>

<span id="example-requests-GETapi-ecard-wallet-verify-payment">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://ecardseva.uonely.com/api/ecard/wallet/verify-payment" \
    --header "Authorization: Bearer Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"order_id\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://ecardseva.uonely.com/api/ecard/wallet/verify-payment"
);

const headers = {
    "Authorization": "Bearer Bearer {YOUR_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "order_id": "consequatur"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ecard-wallet-verify-payment">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ecard-wallet-verify-payment" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ecard-wallet-verify-payment"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ecard-wallet-verify-payment"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ecard-wallet-verify-payment" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ecard-wallet-verify-payment">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ecard-wallet-verify-payment" data-method="GET"
      data-path="api/ecard/wallet/verify-payment"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ecard-wallet-verify-payment', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ecard-wallet-verify-payment"
                    onclick="tryItOut('GETapi-ecard-wallet-verify-payment');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ecard-wallet-verify-payment"
                    onclick="cancelTryOut('GETapi-ecard-wallet-verify-payment');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ecard-wallet-verify-payment"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
            <p>
            <small class="badge badge-grey">OPTIONS</small>
            <b><code>api/ecard/wallet/verify-payment</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ecard-wallet-verify-payment"
               value="Bearer Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ecard-wallet-verify-payment"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-ecard-wallet-verify-payment"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>order_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_id"                data-endpoint="GETapi-ecard-wallet-verify-payment"
               value="consequatur"
               data-component="body">
    <br>
<p>The transaction/order ID. Example: <code>consequatur</code></p>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
