var nodemailer = require('nodemailer');

var transporter = nodemailer.createTransport({
  service: 'okmailcas.oksystem.local',
  auth: {
    user: '',
    pass: ''
  }
});

var mailOptions = {
  from: 'pikl@oksystem.cz',
  to: 'fjpikl@gmail.com',
  subject: 'Sending Email using Node.js',
  text: 'That was easy!'
};

transporter.sendMail(mailOptions, function(error, info){
  if (error) {
    console.log(error);
  } else {
    console.log('Email sent: ' + info.response);
  }
}); 