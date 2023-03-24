

<!DOCTYPE html>
<html>


<body>

<main>
  <br>
<h2>Project requirements information</h2><br>
Register with the system.             (10% Total) <br>
  • The system should allow users to register with the system using a username and password.
  • Complexity rules regarding the password should be enforced.
  • Password storage should be salted and hashed. <br>


On an unsuccessful authentication attempt           (20% Total) <br>
A generic error message is presented back to the end user outlining that the username & password combination cannot be authenticated at the moment. ie… “The username Richard and password could not be authenticated at the moment”. Note that the username supplied during the authentication attempt is reflected back to the user interface in the event of an unsuccessful login attempt. <br>
  • Reflect the supplied username provided in the above message. Ensure that this reflected parameter in not susceptible to XSS. You are to write your own sanitisation code for characters that can be utilised for XSS. <br>
  • Lockout after 5 attempts for 3 minutes.<br><br>
<br>
On successful authentication              (15% Total) <br>
  • The system should greet the user by their username.<br>
  • Create an active authenticated session.<br>
  • Allow for the authenticated user to view some pages (at least two) that an unauthenticated user will not have access to. <br>
  • Allow for the user to logout securely. <br>
  • Lockout after 10 minutes of inactivity.<br>
  • Max session duration of one hour irrespective of in session activity.<br><br>


Password Change                 (15% Total) <br>
  • Authenticated users should be capable of changing their password.<br>
  • Complexity rules regarding the password should be enforced.<br>
  • On password change the active session should be expired.<br>
  • The user will have to re-authenticate using new credentials to gain access to the system.<br>
  • No out of band communication, mechanism is required to inform the user that their credentials has been updated. <br>
  • You are to implement Cross Site Request Forgery (CSRF) protection on this page. <br>
  • Note: “In the real world” the values contained in this request would be passed as a POST request. However, to expedite the correction & testing of this assignment you are to pass the values for this functionality in a HTTP GET request.<br><br>

Event Log & ADMIN user              (10% Total)<br>
  • Your application should store unsuccessful and successful login attempts to an event log. This event log should accessible and viewable to the authenticated user “ADMIN” only. <br>
  • This users authentication details are as follows<br>
Username = “ADMIN” 
Password  = “SaD_2023!”   
  • This account is to be created, when your database is being created.<br><br>


Testing               (30% for Test Cases and Results)<br>
Your documentation should include security test cases and test results for all implemented functionality.<br>
In this component of the report should clearly highlight what security features you are assessing, the vulnerability type you are testing for, the tests you performed along with your results.



</main>

</body>

</html>


