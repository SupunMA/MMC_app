<!--==========================
      Contact Section
    ============================-->
    <section id="contact">
        <div class="container-fluid">

            <div class="section-header">
                <h3>Contact Us</h3>
            </div>

            <div class="row wow fadeInUp">

                <div class="col-lg-6">
                    <div class="map mb-4 mb-lg-0">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d989.9330755648917!2d80.1252581292212!3d7.0407141306741385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae301b74bd30b09%3A0x15c5bb473efa2599!2sWeliweriya%20-%20Kirindiwela%20Rd!5e0!3m2!1sen!2slk!4v1642921780220!5m2!1sen!2slk" width="800" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-5 info">
                            <i class="ion-ios-location-outline"></i>
                            <p>Madhushanka Micro Credit (Pvt) Ltd,</p>
                        </div>
                        <div class="col-md-4 info">
                            <i class="ion-ios-email-outline"></i>
                            <p>aththanagodha@gmail.com</p>
                        </div>
                        <div class="col-md-3 info">
                            <i class="ion-ios-telephone-outline"></i>
                            <p>0113 682 800</p>
                        </div>
                    </div>

                    <div class="form">
                        <div id="sendmessage">Your message has been sent. Thank you!</div>
                        <div id="errormessage"></div>
                        <form action="" method="post" role="form" class="contactForm">
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Your Name" data-rule="minlen:4"
                                        data-msg="Please enter at least 4 chars" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Your Email" data-rule="email"
                                        data-msg="Please enter a valid email" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="sel1">Select The Branch</label>

                                    <select class="form-control" id="sel1" data-rule="required">

                                        @foreach ($bdata as $oneBranch)
                                            <option value="{{$oneBranch->branchID}}">{{$oneBranch->branchName}}</option>
                                        @endforeach

                                    </select>
                                    <div class="validation"></div>
                                </div>

                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Subject" data-rule="minlen:4"
                                    data-msg="Please enter at least 8 chars of subject" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" data-rule="required"
                                    data-msg="Please write something for us" placeholder="Message"></textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="text-center"><button type="submit" title="Send Message">Send
                                    Message</button></div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- #contact -->