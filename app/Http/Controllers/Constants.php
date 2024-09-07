<?php

namespace App\Http\Controllers;

class Constants
{

    const SUBMISSION_FROM_TEACHER = "teacher";
    const SUBMISSION_FROM_STUDENT = "student";



    const HTTP_UNAUTH = "unauthorized";
    const HTTP_USER_BLOCKED = "user blocked";
    const HTTP_NOT_FOUND = "not found";
    const HTTP_BAD_REQUEST = "bad request";
    const HTTP_THIS_DIVISION_NOT_ASSIGNED_TO_THIS_TEACHER = "This is Division Not Assigned To This is Teacher";
    const HTTP_OLD_PASSWORD_NOT_MATCH = "old password not match";
    const HTTP_NEW_PASSWORD_SAME_OLD = "new password same old";
    const HTTP_LOGGED_SUCCESS = "Success logged in";
    const HTTP_UPDATE_DATA = "Success updated  ";
    const HTTP_LOGOUT = "Success logout  ";   
    const HTTP_DELETE_DATA = "Success deleted  ";
    const HTTP_AN_ERROR_OCCURRED = "An error occurred, try again";
    const HTTP_END_MEETINGROOM   = "Success end meeting room";
    const HTTP_UPLOAD_CONTENT_DIRECTORY   = "Success Upload Directory";
    const HTTP_UPLOAD_CONTENT_FILE_SUCCESS   = "Success Upload File";
    const HTTP_UPLOAD_CONTENT_URL_SUCCESS  = "Success Upload URL";
    const HTTP_THIS_DIRECTORY_ALREADY_EXIST   = "This Directory Already Exist";
    const HTTP_THIS_File_ALREADY_EXIST   = "This File Already Exist";
    const HTTP_THIS_URL_ALREADY_EXIST   = "This URL Already Exist";
    const HTTP_CREATE_DIRECTORY   = "Success Create Directory";
    const HTTP_ADD_PERMISSIONS_FILES   = "Success add permissions files";
    const HTTP_REMOVE_PERMISSIONS_FILES   = "Success remove permissions files";
    const HTTP_NUMBER_PHANE_NOT_FOND   = "Error number phone not fond";
    const HTTP_SUCSSECC_REGESTER_FORM   = "Success regester form";
    const HTTP_SUCSSECC_NEW_ASSIGNMENTS_SUBMISSION = " Success new assignmts submission ";
    const HTTP_SUCSSES_SEND_NOTIFICATIONS = "Success send notifications";
    const HTTP_NOTIFICATION_NOT_FOUND = "This is Notification Not Found";
    const HTTP_SUCSSES_THE_EXAM_HAS_BEEN_SUBMITTED = "Success the exam has been submitted";
    const HTTP_SUCCESS_READ_NOTIFICATION = "success read notification";
    const HTTP_SUCCESS_ALL_READ_NOTIFICATION = "success all read notification";
  
    const  JOB_TITLE_CHAT = ["member", "supervisor"];

}
