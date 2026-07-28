<?php
session_start();
require_once '../../connection.php';

if (isset($_POST["register"])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $company_name = $_POST['company_name'];
    $business_entity = $_POST['business_entity'];
    $business_sector = $_POST['business_sector'];
    $business_website = $_POST['business_website'] === '' ? null : $_POST['business_website'];
    $business_description = $_POST['business_description'] ?? '';

    $country = $_POST['country'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $subdistrict = $_POST['subdistrict'];
    $business_address = $_POST['business_address'];
    
    $company_email = $_POST['company_email'];
    $company_phone = $_POST['company_phone'];

    $check_user_email = count($database->select('user', 'email', [
        'email' => $email
    ]));

    $check_company_email = count($database->select('company', 'email', [
        'email' => $email
    ]));

    $check_company_phone = count($database->select('company', 'phone', [
        'phone' => $company_phone
    ]));

    if ($check_user_email > 0) {
        echo '<script>alert("User email already exists. Please use a different email.")</script>';
    } elseif ($check_company_email > 0) {
        echo '<script>alert("Company email already exists. Please use a different email.")</script>';
    } elseif ($check_company_phone > 0) {
        echo '<script>alert("Company phone already exists. Please use a different phone.")</script>';
    } else {
        $company = $database->insert('company', [
            'name' => $company_name,
            'email' => $company_email,
            'phone' => $company_phone,
            'business_entity' => $business_entity,
            'sector' => $business_sector,
            'website' => $business_website,
            'description' => $business_description,
            'country' => $country,
            'province' => $province,
            'city' => $city,
            'subdistrict' => $subdistrict,
            'address' => $business_address,
        ]);

        $company_id = $database->id();

        $user = $database->insert('user', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'company_id' => $company_id,
        ]);

        if ($user) {
            echo '<script>alert("Registration successful. Please log in.")</script>';
            echo '<script>window.location.href = "login.php";</script>';
        } else {
            echo '<script>alert("Error occurred during registration.")</script>';
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="../../../assets/css/register.css">
</head>

<body class="register-page bg-body-secondary">
    <div class="wizard-wrapper">
        <div class="card">
            <div class="card-body p-4">
                <!-- Step indicators -->
                <ol class="wizard-steps mb-4" id="wizard-steps">
                    <li class="active" data-step="0">Account</li>
                    <li data-step="1">Profile</li>
                    <li data-step="2">Address</li>
                    <li data-step="3">Contact</li>
                    <li data-step="4">Review</li>
                </ol>

                <!-- Form -->
                <form id="wizard-form" novalidate="" method="POST">
                    <!-- Step 1 -->
                    <fieldset class="wizard-step" data-step="0">
                        <h2 class="h5 mb-3">Create your account</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="wz-email">Email<span class="required-indicator sr-only"> (required)</span></label>
                                <input type="email" class="form-control" id="wz-email" name="email" placeholder="name@example.com" required="">
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-username"> Username <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-username" name="username" placeholder="e.g. johndoe" required="" minlength="3">
                                <div class="invalid-feedback">
                                    Username must be at least 3 characters.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-password"> Password <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="password" class="form-control" id="wz-password" name="password" placeholder="Minimum 8 characters" required="" minlength="8">
                                <div class="invalid-feedback">
                                    Password must be at least 8 characters.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-password2"> Confirm password <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="password" class="form-control" id="wz-password2" name="password_confirm" placeholder="Re-enter your password" required="">
                                <div class="invalid-feedback">Passwords must match.</div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Step 2 -->
                    <fieldset class="wizard-step d-none" data-step="1">
                        <h2 class="h5 mb-3">Company Information</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="wz-company-name"> Company Name <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-company-name" name="company_name" placeholder="e.g. Red Hat, Inc." required="">
                                <div class="invalid-feedback">Company name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-business-entity"> Business Entity <span class="required-indicator sr-only"> (required)</span></label>
                                <select class="form-select" id="wz-business-entity" name="business_entity" required="">
                                    <option value="" disabled selected>Choose…</option>
                                    <option value="PT">PT</option>
                                    <option value="CV">CV</option>
                                    <option value="UD">UD</option>
                                    <option value="Firma">Firma</option>
                                    <option value="Koperasi">Koperasi</option>
                                    <option value="Perorangan">Perorangan</option>
                                </select>
                                <div class="invalid-feedback">Please select a business entity.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-business-sector"> Business Sector <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-business-sector" name="business_sector" placeholder="e.g. Open Source Software" required="">
                                <div class="invalid-feedback">Business sector is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-business-website"> Business Website </label>
                                <input type="url" class="form-control" id="wz-business-website" name="business_website" placeholder="https://example.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="wz-business-description"> Business Description</label>
                                <textarea class="form-control" id="wz-business-description" name="business_description" rows="3" placeholder="Briefly describe what your company does"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Step 3 -->
                    <fieldset class="wizard-step d-none" data-step="2">
                        <h2 class="h5 mb-3">Company Address</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="wz-country"> Country <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-country" name="country" placeholder="e.g. Indonesia" required="">
                                <div class="invalid-feedback">Country is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-province"> Province <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-province" name="province" placeholder="e.g. North Carolina" required="">
                                <div class="invalid-feedback">Province is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-city"> City/Regency <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-city" name="city" placeholder="e.g. Raleigh" required="">
                                <div class="invalid-feedback">City/Regency is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-subdistrict"> Subdistrict <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="text" class="form-control" id="wz-subdistrict" name="subdistrict" placeholder="e.g. Downtown Raleigh" required="">
                                <div class="invalid-feedback">Subdistrict is required.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="wz-business-address"> Company Address <span class="required-indicator sr-only"> (required)</span></label>
                                <textarea class="form-control" id="wz-business-address" name="business_address" rows="2" placeholder="e.g. 100 East Davie Street, Raleigh, NC 27601, United States" required=""></textarea>
                                <div class="invalid-feedback">Company address is required.</div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Step 4 -->
                    <fieldset class="wizard-step d-none" data-step="3">
                        <h2 class="h5 mb-3">Company Contact</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="wz-company-email"> Company Email <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="email" class="form-control" id="wz-company-email" name="company_email" placeholder="company@example.com" required="">
                                <div class="invalid-feedback">Please enter a valid company email.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="wz-company-phone"> Company Phone Number <span class="required-indicator sr-only"> (required)</span></label>
                                <input type="tel" class="form-control" id="wz-company-phone" name="company_phone" placeholder="08xxxxxxxxxx" required="">
                                <div class="invalid-feedback">Company phone number is required.</div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Step 5 -->
                    <fieldset class="wizard-step d-none" data-step="4">
                        <h2 class="h5 mb-3">Review &amp; confirm</h2>
                        <div class="row g-3 mb-3" id="wz-summary"></div>
                    </fieldset>

                    <!-- Navigation -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" id="wz-prev">
                            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                            Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="wz-next">
                            Next
                            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </button>
                        <button type="submit" class="btn btn-success d-none" id="wz-submit" name="register" value="1">
                            <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/lte-theme.js"></script>
    <script src="../../../assets/js/register.js"></script>
</body>

</html>