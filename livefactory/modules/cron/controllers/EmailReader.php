<?php

namespace livefactory\modules\cron\controllers;

use Yii;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class EmailReader {

	// imap server connection
	public $conn;

	// inbox storage and inbox message count
	public $inbox;
	public $msg_cnt;

	// email login credentials
	private $server;
	private $user;
	private $pass;
	private $port;
	private $security;
	private $type;
	private $debug;

	// connect to the server and get the inbox emails
	function __construct($params) {
		
		echo "<pre>";
		$this->debug = Yii::$app->params['SHOW_DEBUG_TOOLBAR'];
		$this->server = $params['server'];
		$this->user = $params['user'];
		$this->pass = $params['pass'];
		$this->port = $params['port'];
		$this->security = $params['security'];
		$this->type = $params['type'];

		$this->connect();

		$this->inbox();

		if($this->debug == "Yes")
		print_r($this);
		
	}

	// close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}

	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	function connect() {

		if($this->type == 'pop')
		{
			$this->conn = imap_open('{'.$this->server.':'.$this->port.'/pop/ssl/novalidate-cert}', $this->user, $this->pass, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN'));
		}
		else
		{
			$this->conn = imap_open('{'.$this->server.':'.$this->port.'/imap/ssl/novalidate-cert}Inbox', $this->user, $this->pass, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN', 'DISABLE_AUTHENTICATOR' => 'GSSAPI'));
		}

		if($this->debug == "Yes")
		{
			print_r(imap_errors());
		}
	}

	function getFromEmail($index)
	{
		return $this->inbox[$index]['header']->from[0]->mailbox . "@" . $this->inbox[$index]['header']->from[0]->host;
	}

	// move the message to a new folder
	function move($msg_index, $folder) {
		// move on server
		if (!imap_mail_move($this->conn, $msg_index, $folder))
		{
			// If folder not available then delete the mail
			echo "Processed folder (".$folder.") missing! Deleting the message from mailbox"."<br/>\n";
			$this->delete($msg_index);
		}
	}

	// delete the message
	function delete($msg_index) {
		// delete on server
		imap_delete($this->conn, $msg_index);
	}

	// get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index=NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}

		return $this->inbox[0];
	}

	// read the inbox
	function inbox() {

		
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => nl2br(imap_fetchbody($this->conn, $i, 1)),
				'structure' => imap_fetchstructure($this->conn, $i)
			);
		}

		$this->inbox = $in;
	}

}