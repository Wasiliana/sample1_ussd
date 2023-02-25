## Laravel Wasiliana USSD Payment Integration Sample

This is a Laravel application that implements a USSD payment integration using the Wasiliana API. The code handles USSD sessions and prompts users for payment via the M-Pesa mobile payment platform.

  

## Installation

To install the application, follow these steps:
 1. Clone the repository to your local machine.
 2. Install dependencies by running `composer install`.
 3. Create a `.env` file from the `.env.example` file.
 4.  Configure your application settings in the `.env` file.
 5.  Run the migrations by running `php artisan migrate`.
 6. Start the development server by running `php artisan serve.`

## Usage

The application handles USSD sessions and prompts users for payment via the M-Pesa mobile payment platform. The `trigger_stk()` function handles the payment integration.
To test the application, use a tool such as `ngrok` to expose your local development server to the internet. Then, set the Wasiliana API callback URL to your ngrok URL.

## Implementation

The implementation is divided into two main parts:

 1. The USSD controller: This controller handles USSD requests and
    responses. It is responsible for interacting with the user and
    handling user input.
 2. The STK trigger: This is a separate function that is responsible for triggering the MPESA STK push. It is called from the USSD controller when the user confirms the payment.
 

> The USSD controller works as follows:

 1. When a user dials the USSD code, a new session is started if the
    user has not used the service before.																			
 2. If the user has a session, the USSD controller checks the step the
    user is in and prompts the user accordingly.
 3. If the user enters an invalid input, the controller re-prompts the
    user and saves the user's progress.
 4. If the user confirms the payment, the STK trigger function is called
    to complete the payment.

> The STK trigger function works as follows:

 1. The STK trigger function takes in the payment amount and the user's
    phone number as parameters.
 2. The function triggers the MPESA STK push by making a request to the
        MPESA STK link with the required parameters.

  

## Credits

This application was developed by Joseph Kitonga.