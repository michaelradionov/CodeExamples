# CodeExamples
Just a couple of latest examples of my code. Both are taken from GoGit.ru project. I choose them because they are self representative anough.

## Mail unsubscription
Here we have a tiny class with one method which I wrote to handle incoming Mailgun API requests. These requests are telling that a user has been unsubscribed and why. I think there is no need foк further description.

## Policies
This piece is a little bit more complicated because it is based on Laravel routing system which is really great BTW. So here we got 3 roles: guest, authienticated user and paid student.
- Guests can do nothing.
- Registered users can see lessons listing, but when they try to proceed to single lessons page they are getting redirected to Wait4Webinar page. This is a marketing trick.
- Students (paid members) can visit first lessons page with all that stuff (slides, practice, testing, webinar). But if they want to get to the any other lesson they must complete all of the previous lesson's practices and testings. I believe you get the idea.
