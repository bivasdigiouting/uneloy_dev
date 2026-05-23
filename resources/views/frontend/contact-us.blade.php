@extends('layouts.public')

@section('content')
<div class="container" style="padding: 50px 0;">
    <div class="row">
        <div class="col-12">
            <h1>Contact Us</h1>
            <div class="content-body mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Get in Touch</h4>
                        <p>We'd love to hear from you. Please contact us using the information below.</p>
                        
                        <div class="mt-4">
                            <h5>Address</h5>
                            <p>
                                Uonely Solutions Pvt. Ltd.<br>
                                TD1/50B, Dhalipara, Teghoria,<br>
                                Kolkata 700157, West Bengal
                            </p>
                        </div>

                        <div class="mt-4">
                            <h5>Phone</h5>
                            <p><a href="tel:+912269645986">+91 22 6964 5986</a></p>
                        </div>

                        <div class="mt-4">
                            <h5>Email</h5>
                            <p><a href="mailto:support@uonely.com">support@uonely.com</a></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Optional: Contact Form could go here -->
                         <div class="card">
                            <div class="card-body">
                                <h5>Send us a message</h5>
                                <form>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="Your Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" placeholder="Your Email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="3" placeholder="Your Message"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
