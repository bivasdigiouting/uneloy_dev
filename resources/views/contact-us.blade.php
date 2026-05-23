@extends('layouts.public')

@section('content')
<div class="container" style="padding-top: 50px; padding-bottom: 50px;">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1 style="margin-bottom: 30px; color: #333;">Contact Us</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <h3 style="margin-bottom: 20px;">Get in Touch</h3>
            <p style="margin-bottom: 20px;">We are here to help you. Please fill out the form below or reach out to us using the contact details.</p>
            
            <form action="#" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="background-color: #f05a28; border-color: #f05a28; padding: 10px 30px;">Send Message</button>
            </form>
        </div>
        
        <div class="col-xs-12 col-md-6" style="padding-left: 30px;">
            <h3 style="margin-bottom: 20px;">Contact Information</h3>
            
            <div style="margin-bottom: 20px;">
                <h4 style="font-size: 18px; font-weight: bold;">Address</h4>
                <p>Uonely Solutions Pvt. Ltd.<br>
                TD1/50B, Dhalipara, Teghoria,<br>
                Kolkata 700157, West Bengal</p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h4 style="font-size: 18px; font-weight: bold;">Phone</h4>
                <p><a href="tel:+912269645986" style="color: #333;">+91 22 6964 5986</a></p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h4 style="font-size: 18px; font-weight: bold;">Email</h4>
                <p><a href="mailto:support@uonely.com" style="color: #333;">support@uonely.com</a></p>
            </div>

            <div style="margin-top: 30px;">
                <h4 style="font-size: 18px; font-weight: bold;">Follow Us</h4>
                <div style="display: flex; gap: 10px;">
                    <!-- Icons would go here, reusing existing assets if possible or FA icons -->
                    <a href="https://www.facebook.com/profile.php?id=100087455809886" target="_blank" style="margin-right: 10px;"><i class="fa fa-facebook fa-2x"></i></a>
                    <a href="https://mobile.twitter.com/novabizg" target="_blank" style="margin-right: 10px;"><i class="fa fa-twitter fa-2x"></i></a>
                    <a href="https://www.instagram.com/novabiz11/" target="_blank" style="margin-right: 10px;"><i class="fa fa-instagram fa-2x"></i></a>
                    <a href="https://www.youtube.com/@NBG101" target="_blank"><i class="fa fa-youtube-play fa-2x"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
