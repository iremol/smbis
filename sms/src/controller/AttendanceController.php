<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace sms\controller;

use DateInterval;
use DateTime;
use DateTimeZone;
use sms\domain\Attendance;
use sms\model\AttendanceModel;
use sms\util\UniqueId;

/**
 * Description of AttendanceController
 *
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 *         #@author root
 */
class AttendanceController extends AbstractController
{

    public function persist()
    {
        session_start();
        // if (!isset($_SESSION['uname'])) {
        // header("Location: /mgt/login");
        // }
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();

        $properties = [
            'uname' => $cookies->get('uname')
        ];

        $attendance = new Attendance();
        $attendance->setVattreg(($params->getString("vattendanceid")));
        $attendance->setDstartdate(($params->getString("dstartdate")));
        $attendance->setDenddate(($params->getString("denddate")));

        $model = new AttendanceModel($this->db['db_mgt']);
        // $this->createRegister("test", $attendance->getDstartdate(), $attendance->getDenddate()); // Just for testing purposes
        $result = $model->persist($attendance);

        return $result;
    }

    /**
     *
     * @return array of Attendance
     */
    private function getAll(): array
    {
        session_start();
        if (! isset($_SESSION['uname'])) {
            return $this->render("management/mgtlogin.twig", []);
        }
        return $model = (new AttendanceModel($this->db["db_mgt"]))->getAll();
    }

    public function showNonGenerated()
    {
        session_start();
        $this->sessionEnforcer($this, $_SESSION['uname'], "management/mgtlogin.twig"); // enforces valid session
        $arr = $this->getAll();
        $params = [
            "uname" => $_SESSION['uname'],
            "arr" => $arr
        ];
        return $this->render('management/inactive_attendance.twig', $params);
    }

    /**
     * Generates the column names for the Register table
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function dateGenerator(string $startDate, string $endDate): array
    {
        $sd = new DateTime($startDate, new DateTimeZone("Africa/Lagos")); // instance of a date equal to the attendance initial date
        $ed = new DateTime($endDate, new DateTimeZone("Africa/Lagos")); // instance of a date equal to the attendance enddate
        $dateInterval = new DateInterval("P1D"); // represents a day interval
        $arr = []; // array to hols the generated dates
        $index = 0; // array index
        while ($sd != $ed) { // loop to generate accurate days for the register
            if (($sd->format('D') == 'Sat') || ($sd->format('D') == 'Sun')) { // make sure saturday & sundays are not included
                $sd->add($dateInterval); // adds a day to the startdate
                continue;
            }
            $arr[$index] = $sd->format('Y-m-d'); // add valid date to the array
            $sd->add($dateInterval); // adds a day to the startdate
            $index ++; // increment index by 1
        }
        return $arr;
    }

    /**
     * Access function to the createRegister function in AttendanceModel
     *
     * @param string $tablename
     * @param
     *            $startdate
     * @param
     *            $enddate
     * @return bool
     */
    // public function createRegister($tablename, $startDate, $endDate): string
    public function createRegister(): string
    {
        session_start();
        $this->sessionEnforcer($this, $_SESSION["uname"], "management/mgtlogin.twig"); // enforces login
        $params = $this->request->getParams();
        if ((new AttendanceModel($this->db["db_mgt"]))->createRegister("mgt." . 'register', $this->dateGenerator($params->getString("dstartdate"), $params->getString("denddate")))) { // calls the createRegister function)
            return $this->showCreateAttendance("School Attendance successfully created.");
        } else {
            return $this->showCreateAttendance("School Attendance creation failed.");
        }
    }

    /**
     * showCreateAttendance()
     *
     * @return string
     */
    public function showCreateAttendance($msg = ""): string
    {
        session_start(); // resumes the session
        $this->sessionEnforcer($this, $_SESSION["uname"], "/management/mgtlogin.twig"); // checks if session is valid
        $att_id = (new UniqueId())->generateAttId(); // generates the attendance id
        $dayp = (new DayPatternController($this->di, $this->request))->getAll(); // returns all day patterns
        $duration_rec = (new ClassDurationController($this->di, $this->request))->getAll(); // array containing all duration records
        $this->log->info('[' . $_SESSION['uname'] . "logged in " . date('d-M-Y H:i:s') . "]");
        return $this->render("management/createregister.twig", [
            "uname" => $_SESSION['uname'],
            "att_id" => $att_id,
            "dp" => $dayp,
            "cd" => $duration_rec,
            "msg" => $msg
        ]); // returns the createattendance twig file
    }
}
