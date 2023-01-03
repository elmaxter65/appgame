<?php

use App\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages = Message::all();
        if($messages->isEmpty()){
            DB::table('messages')->insert([
                'id' => 1,
                'en' => 'Abbott Education Network',
                'location'=> 'Splash',
            ]);
            DB::table('messages')->insert([
                'id' => 2,
                'en' => 'Login',
                'location'=> 'Login',
            ]);
            DB::table('messages')->insert([
                'id' => 3,
                'en' => 'Log in with your email',
                'location'=> 'Login',
            ]);
            DB::table('messages')->insert([
                'id' => 4,
                'en' => 'Are you new here? Create an account',
                'location'=> 'Login',
            ]);
            DB::table('messages')->insert([
                'id' => 5,
                'en' => 'Create an account',
                'location'=> 'Register',
            ]);
            DB::table('messages')->insert([
                'id' => 6,
                'en' => 'Register with your email',
                'location'=> 'Register',
            ]);
            DB::table('messages')->insert([
                'id' => 7,
                'en' => 'Do you an account? Log in',
                'location'=> 'Register',
            ]);
            DB::table('messages')->insert([
                'id' => 8,
                'en' => 'Email',
                'location'=> 'Register_Form',
            ]);
            DB::table('messages')->insert([
                'id' => 9,
                'en' => 'Password',
                'location'=> 'Register_Form',
            ]);
            DB::table('messages')->insert([
                'id' => 10,
                'en' => 'Repeat password',
                'location'=> 'Register_Form',
            ]);
            DB::table('messages')->insert([
                'id' => 11,
                'en' => 'CREATE ACCOUNT',
                'location'=> 'Register_Form',
            ]);
            DB::table('messages')->insert([
                'id' => 12,
                'en' => 'Success! Check your email. We´ve send you an email to confirm your registration',
                'location'=> 'Register_Popup',
            ]);
            DB::table('messages')->insert([
                'id' => 13,
                'en' => 'CONTINUE',
                'location'=> 'Register_Popup',
            ]);
            DB::table('messages')->insert([
                'id' => 14,
                'en' => 'Invalid email address',
                'location'=> 'Login_Error',
            ]);
            DB::table('messages')->insert([
                'id' => 15,
                'en' => 'Password must be at least 6 long',
                'location'=> 'Login_Error',
            ]);
            DB::table('messages')->insert([
                'id' => 16,
                'en' => 'Forgot your password?',
                'location'=> 'Login',
            ]);
            DB::table('messages')->insert([
                'id' => 17,
                'en' => 'Log in with your email',
                'location'=> 'Login',
            ]);
            DB::table('messages')->insert([
                'id' => 18,
                'en' => 'Are you new here? Create an account',
                'location'=> 'Login',
            ]);

        //Welcomee
            DB::table('messages')->insert([
                'id' => 19,
                'en' => 'Welcome to OCT-PRO!',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 20,
                'en' => 'An Abbott aplication to learn about OCT',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 21,
                'en' => 'Nickname',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 22,
                'en' => 'Name',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 23,
                'en' => 'Last Name',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 24,
                'en' => 'City',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 25,
                'en' => 'Country',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 26,
                'en' => 'CONTINUE',
                'location'=> 'Welcome',
            ]);
            DB::table('messages')->insert([
                'id' => 27,
                'en' => 'We want to offer you a personalized content according to your position, these fields are optional',
                'location'=> 'Welcome_2',
            ]);
            DB::table('messages')->insert([
                'id' => 28,
                'en' => 'What hospital do you work at?',
                'location'=> 'Welcome_2',
            ]);
            DB::table('messages')->insert([
                'id' => 29,
                'en' => 'Occupation',
                'location'=> 'Welcome_2',
            ]);
            DB::table('messages')->insert([
                'id' => 30,
                'en' => 'CONTINUE',
                'location'=> 'Welcome_2',
            ]);
            DB::table('messages')->insert([
                'id' => 31,
                'en' => 'Select an avatar to create your profile',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 32,
                'en' => 'MALE',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 33,
                'en' => 'FEMALE',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 34,
                'en' => 'Maybe later',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 35,
                'en' => 'CONTINUE',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 36,
                'en' => 'Updating profile',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 37,
                'en' => 'Getting user profile',
                'location'=> 'Avatar_Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 38,
                'en' => 'Hello, ',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 39,
                'en' => 'POINTS',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 40,
                'en' => 'RANK',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 41,
                'en' => 'ACHIEVEMENTS',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 42,
                'en' => 'MEDAL',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 43,
                'en' => 'Learning topics',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 44,
                'en' => 'Tips',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 45,
                'en' => 'CALCIUM MANAGEMENT WITH OCT',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 46,
                'en' => 'NEWS',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 47,
                'en' => '0 of 1 lessons completed correctly',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 48,
                'en' => 'CHALLENGED LOCKED',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 49,
                'en' => 'IMAGE INTERPRETATION',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 50,
                'en' => 'OCT PROCEDURE',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 51,
                'en' => 'Information contained herein for DISPLAY in Europe, Middle East and Africa. Please check the regulation status of the device before distribution in areas where CE marking is not the regulation in force. Not to be reproduced, distributed or excerpted. 2020 Abbott, All rights reserved. MAT-2008791 v1.0',
                'location'=> 'Home',
            ]);
            DB::table('messages')->insert([
                'id' => 52,
                'en' => 'EDIT PROFILE',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 53,
                'en' => 'SETTINGS',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 54,
                'en' => 'ACHIEVEMENTS',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 55,
                'en' => 'RANKING',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 56,
                'en' => 'Pending',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 57,
                'en' => 'Achievement first test',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 58,
                'en' => 'POINTS',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 59,
                'en' => 'Doing your first test',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 60,
                'en' => 'Achievement first challenge',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 61,
                'en' => 'Doing your first challenge',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 62,
                'en' => 'Achievement level 1 total',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 63,
                'en' => 'Finishing all the games from level 1',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 64,
                'en' => 'Achievement challenges',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 65,
                'en' => 'Finishing all the challenges',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 66,
                'en' => 'Achievement level 1 ',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 67,
                'en' => 'Finishing all the games from level 1 in Calcium management with OCT',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 68,
                'en' => 'Finishing all the games from level 1 in Image interpretation',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 69,
                'en' => 'Finishing all the games from level 1 in OCT procedure',
                'location'=> 'Profile',
            ]);
            DB::table('messages')->insert([
                'id' => 70,
                'en' => 'SETTINGS',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 71,
                'en' => 'Change password',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 72,
                'en' => 'Receive notifications',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 73,
                'en' => 'We only send you notifications when we open new real cases to resolve or new exercises.',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 74,
                'en' => 'Global privacy policy',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 75,
                'en' => 'European privacy policy',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 76,
                'en' => 'Request a rep',
                'location'=> 'Settings',
            ]);
            DB::table('messages')->insert([
                'id' => 77,
                'en' => 'LOG OUT',
                'location'=> 'Settings',
            ]);

            DB::table('messages')->insert([
                'id' => 78,
                'en' => 'CHANGE PASSWORD',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 79,
                'en' => 'To change your password, enter your current password, then your new password (twice) and click Submit.',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 80,
                'en' => 'Your current password',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 81,
                'en' => 'Your new password',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 82,
                'en' => 'Repeat new password',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 83,
                'en' => 'SUBMIT',
                'location'=> 'Change password',
            ]);
            DB::table('messages')->insert([
                'id' => 84,
                'en' => 'EDIT PROFILE',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 85,
                'en' => 'CHANGE AVATAR',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 86,
                'en' => 'Nickname',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 87,
                'en' => 'Name',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 88,
                'en' => 'Last Name',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 89,
                'en' => 'City',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 90,
                'en' => 'Country',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 91,
                'en' => 'Hospital',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 92,
                'en' => 'Occupation',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 93,
                'en' => 'SAVE',
                'location'=> 'Edit profile',
            ]);
            DB::table('messages')->insert([
                'id' => 94,
                'en' => 'Lessons',
                'location'=> 'Calcium mangement',
            ]);
            DB::table('messages')->insert([
                'id' => 95,
                'en' => 'Guided Treatment of Calcified Coronary Artery Disease',
                'location'=> 'Calcium mangement',
            ]);
            DB::table('messages')->insert([
                'id' => 96,
                'en' => '0 of 10 questions completed',
                'location'=> 'Calcium mangement',
            ]);
            DB::table('messages')->insert([
                'id' => 97,
                'en' => 'YOUR MEDAL',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 98,
                'en' => 'Bronze',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 99,
                'en' => 'Medals',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 100,
                'en' => 'Diamond medal',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 101,
                'en' => 'Platinum medal',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 102,
                'en' => 'Gold medal',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 103,
                'en' => 'Silver medal',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 104,
                'en' => 'Bronze medal',
                'location'=> 'Your medal',
            ]);
            DB::table('messages')->insert([
                'id' => 105,
                'en' => 'CALCIUM MANAGEMENT WITH OCT',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 106,
                'en' => 'Guided Treatment of Calcified Coronary Artery Disease',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 107,
                'en' => 'LEVEL',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 108,
                'en' => 'SELECT THE IMAGES THAT CONTAIN CALCIUM',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 109,
                'en' => 'Mark all the correct answers',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 110,
                'en' => 'Photos on file at Abbott',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 111,
                'en' => 'CONFIRM',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 112,
                'en' => 'CANCEL',
                'location'=> 'Calcium mangement with OCT LEVEL',
            ]);
            DB::table('messages')->insert([
                'id' => 113,
                'en' => 'Confirm registration - Abbott OCT',
                'location'=> 'Mail validación',
            ]);
            DB::table('messages')->insert([
                'id' => 114,
                'en' => 'Hello, Welcome to Abbott OCT App. Before you can start using it, you need to confirm your registration.',
                'location'=> 'Mail validación',
            ]);
            DB::table('messages')->insert([
                'id' => 115,
                'en' => 'CONFIRM',
                'location'=> 'Mail validación',
            ]);
            DB::table('messages')->insert([
                'id' => 116,
                'en' => 'You have been successfuly registered',
                'location'=> 'Página validación',
            ]);

        }
    }
}
