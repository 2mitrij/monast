<?php

/*
* Copyright (c) 2008, Diego Aguirre
* All rights reserved.
* 
* Redistribution and use in source and binary forms, with or without modification,
* are permitted provided that the following conditions are met:
* 
*     * Redistributions of source code must retain the above copyright notice, 
*       this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright notice, 
*       this list of conditions and the following disclaimer in the documentation 
*       and/or other materials provided with the distribution.
*     * Neither the name of the DagMoller nor the names of its contributors
*       may be used to endorse or promote products derived from this software 
*       without specific prior written permission.
* 
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
* INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
* LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
* OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
* OF THE POSSIBILITY OF SUCH DAMAGE.
*/

ini_set('error_reporting', 'E_ALL & ~E_NOTICE & ~E_WARNING');

set_time_limit(0);

require_once 'lib/include.php';

$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

function parseMsg($msg)
{
	global $json;
	
	if (strpos($msg, 'Reload: ') === 0)
  	{
  		$time = substr($msg, strlen('Reload: '));
  		$saida = array
  		(
  			'Action' => 'Reload',
  			'time'   => ($time * 1000)
  		);
  		return $saida;
  	}
	
	if (strpos($msg, 'PeerStatus: ') === 0)
	{
		list($Peer, $Status, $Calls) = explode(':::', substr($msg, strlen('PeerStatus: ')));
		$saida = array
		(
			'Action' => 'PeerStatus',
			'Peer'   => $Peer,
			'Status' => $Status,
			'Calls'  => $Calls
		);
		return $saida;
	}
	
	if (strpos($msg, 'NewChannel: ') === 0)
	{	
		list($Channel, $State, $CallerIDNum, $CallerIDName, $Uniqueid, $Monitor) = explode(':::', substr($msg, strlen('NewChannel: ')));
		$saida = array
		(
			'Action'       => 'NewChannel',
			'Channel'      => $Channel, 
			'State'        => $State, 
			'CallerIDNum'  => $CallerIDNum, 
			'CallerIDName' => $CallerIDName, 
			'Uniqueid'     => $Uniqueid,
			'Monitor'      => $Monitor
		);
		return $saida;
	}
	
	if (strpos($msg, 'NewState: ') === 0)
	{	
		list($Channel, $State, $CallerID, $CallerIDName, $Uniqueid) = explode(':::', substr($msg, strlen('NewState: ')));
		$saida = array
		(
			'Action'       => 'NewState',
			'Channel'      => $Channel, 
			'State'        => $State, 
			'CallerID'     => $CallerID, 
			'CallerIDName' => $CallerIDName, 
			'Uniqueid'     => $Uniqueid
		);
		return $saida;
	}
	
	if (strpos($msg, 'Hangup: ') === 0)
	{	
		list($Channel, $Uniqueid, $Cause, $Cause_txt) = explode(':::', substr($msg, strlen('Hangup: ')));
		$saida = array
		(
			'Action'    => 'Hangup',
			'Channel'   => $Channel, 
			'Uniqueid'  => $Uniqueid, 
			'Cause'     => $Cause, 
			'Cause_txt' => $Cause_txt
		);
		return $saida;
	}
	
	if (strpos($msg, 'Dial: ') === 0)
	{	
		list($Source, $Destination, $CallerID, $CallerIDName, $SrcUniqueID, $DestUniqueID) = explode(':::', substr($msg, strlen('Dial: ')));
		$saida = array
		(
			'Action'       => 'Dial',
			'Source'       => $Source, 
			'Destination'  => $Destination, 
			'CallerID'     => $CallerID, 
			'CallerIDName' => $CallerIDName, 
			'SrcUniqueID'  => $SrcUniqueID, 
			'DestUniqueID' => $DestUniqueID
		);
		return $saida;
	}
	
	if (strpos($msg, 'Link: ') === 0)
	{	
		list($Channel1, $Channel2, $Uniqueid1, $Uniqueid2, $CallerID1, $CallerID2, $Seconds) = explode(':::', substr($msg, strlen('Link: ')));
		$saida = array
		(
			'Action'    => 'Link',
			'Channel1'  => $Channel1, 
			'Channel2'  => $Channel2, 
			'Uniqueid1' => $Uniqueid1, 
			'Uniqueid2' => $Uniqueid2, 
			'CallerID1' => $CallerID1, 
			'CallerID2' => $CallerID2,
		    'Seconds'   => $Seconds
		);
		return $saida;
	}
	
	if (strpos($msg, 'Unlink: ') === 0)
	{	
		list($Channel1, $Channel2, $Uniqueid1, $Uniqueid2, $CallerID1, $CallerID2) = explode(':::', substr($msg, strlen('Unlink: ')));
		$saida = array
		(
			'Action'    => 'Unlink',
			'Channel1'  => $Channel1, 
			'Channel2'  => $Channel2, 
			'Uniqueid1' => $Uniqueid1, 
			'Uniqueid2' => $Uniqueid2, 
			'CallerID1' => $CallerID1, 
			'CallerID2' => $CallerID2
		);
		return $saida;
	}
	
	if (strpos($msg, 'NewCallerid: ') === 0)
	{	
		list($Channel, $CallerID, $CallerIDName, $Uniqueid, $CIDCallingPres) = explode(':::', substr($msg, strlen('NewCallerid: ')));
		$saida = array
		(
			'Action'         => 'NewCallerid',
			'Channel'        => $Channel, 
			'CallerID'       => $CallerID, 
			'CallerIDName'   => $CallerIDName,
			'Uniqueid'       => $Uniqueid,
			'CIDCallingPres' => $CIDCallingPres
		);
		return $saida;
	}
	
    if (strpos($msg, 'Rename: ') === 0)
	{	
		list($Oldname, $Newname, $Uniqueid, $CallerIDName, $CallerID) = explode(':::', substr($msg, strlen('Rename: ')));
		$saida = array
		(
			'Action'       => 'Rename',
			'Oldname'      => $Oldname, 
			'Newname'      => $Newname, 
			'Uniqueid'     => $Uniqueid,
		    'CallerIDName' => $CallerIDName,
			'CallerID'     => $CallerID, 
		);
		return $saida;
	}
	
	if (strpos($msg, 'MeetmeCreate: ') === 0)
	{
	    list($Meetme) = explode(':::', substr($msg, strlen('MeetmeCreate: ')));
        $saida = array
        (
            'Action' => 'MeetmeCreate',
            'Meetme' => $Meetme
        );
    	return $saida;
	}
	
	if (strpos($msg, 'MeetmeDestroy: ') === 0)
	{
	    list($Meetme) = explode(':::', substr($msg, strlen('MeetmeDestroy: ')));
        $saida = array
        (
            'Action' => 'MeetmeDestroy',
            'Meetme' => $Meetme
        );
    	return $saida;
	}
	
	if (strpos($msg, 'MeetmeJoin: ') === 0)
	{
	    list($Meetme, $Uniqueid, $Usernum, $Channel, $CallerIDNum, $CallerIDName) = explode(':::', substr($msg, strlen('MeetmeJoin: ')));
        $saida = array
        (
            'Action'       => 'MeetmeJoin',
            'Meetme'       => $Meetme, 
            'Uniqueid'     => $Uniqueid, 
            'Usernum'      => $Usernum,
            'Channel'      => $Channel,
            'CallerIDNum'  => $CallerIDNum, 
            'CallerIDName' => $CallerIDName
        );
    	return $saida;
	}
	
    if (strpos($msg, 'MeetmeLeave: ') === 0)
	{
	    list($Meetme, $Uniqueid, $Usernum, $Duration) = explode(':::', substr($msg, strlen('MeetmeLeave: ')));
        $saida = array
        (
            'Action'       => 'MeetmeLeave',
            'Meetme'       => $Meetme, 
            'Uniqueid'     => $Uniqueid, 
            'Usernum'      => $Usernum, 
            'Duration'     => $Duration 
        );
    	return $saida;
	}
	
	if (strpos($msg, 'ParkedCall: ') === 0)
	{
	    list($Exten, $Channel, $From, $Timeout, $CallerID, $CallerIDName) = explode(':::', substr($msg, strlen('ParkedCall: ')));
    	$saida = array
    	(
    		'Action'       => 'ParkedCall',
    		'Exten'        => $Exten, 
    		'Channel'      => $Channel, 
    		'From'         => $From, 
    		'Timeout'      => $Timeout, 
    	    'CallerID'     => $CallerID,
    		'CallerIDName' => $CallerIDName, 
    	);
    	return $saida;
	}
	
    if (strpos($msg, 'UnparkedCall: ') === 0)
	{
	    list($Exten) = explode(':::', substr($msg, strlen('UnparkedCall: ')));
    	$saida = array
    	(
    		'Action'       => 'UnparkedCall',
    		'Exten'        => $Exten, 
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'CliResponse: ') === 0)
	{
	    list($Response) = explode(':::', substr($msg, strlen('CliResponse: ')));
    	$saida = array
    	(
    		'Action'   => 'CliResponse',
    		'Response' => rawurlencode($Response),  
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'AddQueueMember: ') === 0)
	{
	    list($Queue, $Member, $MemberName, $Penalty, $CallsTaken, $LastCall, $Status, $Paused) = explode(':::', substr($msg, strlen('AddQueueMember: ')));
    	$saida = array
    	(
    		'Action'     => 'AddQueueMember',
    		'Queue'      => $Queue,
			'Member'     => $Member,
			'MemberName' => $MemberName,
    		'Penalty'    => $Penalty, 
			'CallsTaken' => $CallsTaken, 
			'LastCall'   => $LastCall, 
			'Status'     => $Status, 
			'Paused'     => $Paused
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'RemoveQueueMember: ') === 0)
	{
	    list($Queue, $Member, $MemberName) = explode(':::', substr($msg, strlen('RemoveQueueMember: ')));
    	$saida = array
    	(
    		'Action'     => 'RemoveQueueMember',
    		'Queue'      => $Queue,
			'Member'     => $Member,
			'MemberName' => $MemberName
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'QueueMemberStatus: ') === 0)
	{
	    list($Queue, $Member, $Penalty, $CallsTaken, $LastCall, $Status, $Paused) = explode(':::', substr($msg, strlen('QueueMemberStatus: ')));
    	$saida = array
    	(
    		'Action'     => 'QueueMemberStatus',
    		'Queue'      => $Queue,
			'Member'     => $Member,
    		'Penalty'    => $Penalty, 
			'CallsTaken' => $CallsTaken, 
			'LastCall'   => $LastCall, 
			'Status'     => $Status, 
			'Paused'     => $Paused
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'AddQueueClient: ') === 0)
	{
	    list($Queue, $Uniqueid, $Channel, $CallerID, $CallerIDName, $Position, $Count, $Wait) = explode(':::', substr($msg, strlen('AddQueueClient: ')));
    	$saida = array
    	(
    		'Action'       => 'AddQueueClient',
			'Queue'        => $Queue,
			'Uniqueid'     => $Uniqueid, 
			'Channel'      => $Channel, 
			'CallerID'     => $CallerID, 
			'CallerIDName' => $CallerIDName, 
			'Position'     => $Position, 
			'Count'        => $Count,
    		'Wait'         => $Wait
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'RemoveQueueClient: ') === 0)
	{
	    list($Queue, $Uniqueid, $Channel, $Count, $Cause) = explode(':::', substr($msg, strlen('RemoveQueueClient: ')));
    	$saida = array
    	(
    		'Action'       => 'RemoveQueueClient',
			'Queue'        => $Queue,
			'Uniqueid'     => $Uniqueid, 
			'Channel'      => $Channel, 
			'Count'        => $Count,
    		'Cause'        => $Cause
    	);
    	return $saida;
	}
	
	if (strpos($msg, 'AddQueueMemberCall: ') === 0)
	{
		list($Queue, $Member, $Uniqueid, $Channel, $CallerID, $Seconds) = explode(':::', substr($msg, strlen('AddQueueMemberCall: ')));
		$saida = array
		(
			'Action'   => 'AddQueueMemberCall',
			'Queue'    => $Queue,
			'Member'   => $Member,
			'Uniqueid' => $Uniqueid, 
			'Channel'  => $Channel, 
			'CallerID' => $CallerID,
			'Seconds'  => $Seconds 
		);
		return $saida;
	}
	
	if (strpos($msg, 'RemoveQueueMemberCall: ') === 0)
	{
		list($Queue, $Member, $Uniqueid) = explode(':::', substr($msg, strlen('RemoveQueueMemberCall: ')));
		$saida = array
		(
			'Action'   => 'RemoveQueueMemberCall',
			'Queue'    => $Queue,
			'Member'   => $Member,
			'Uniqueid' => $Uniqueid
		);
		return $saida;
	}
	
	if (strpos($msg, 'QueueParams: ') === 0)
	{
		list($Queue, $Max, $Calls, $Holdtime, $Completed, $Abandoned, $ServiceLevel, $ServicelevelPerf, $Weight) = explode(':::', substr($msg, strlen('QueueParams: ')));
		$saida = array
		(
			'Action'           => 'QueueParams',
			'Queue'            => $Queue,
			'Max'              => $Max, 
			'Calls'            => $Calls, 
			'Holdtime'         => $Holdtime, 
			'Completed'        => $Completed, 
			'Abandoned'        => $Abandoned, 
			'ServiceLevel'     => $ServiceLevel,
			'ServicelevelPerf' => $ServicelevelPerf,
			'Weight'           => $Weight
		);
		return $saida;
	}
	
	if (strpos($msg, 'MonitorStart: ') === 0)
	{
		list($Channel, $Uniqueid) = explode(':::', substr($msg, strlen('MonitorStart: ')));
		$saida = array
		(
			'Action'   => 'MonitorStart',
			'Channel'  => $Channel,
			'Uniqueid' => $Uniqueid
		);
		return $saida;
	}
	
	if (strpos($msg, 'MonitorStop: ') === 0)
	{
		list($Channel, $Uniqueid) = explode(':::', substr($msg, strlen('MonitorStop: ')));
		$saida = array
		(
			'Action'   => 'MonitorStop',
			'Channel'  => $Channel,
			'Uniqueid' => $Uniqueid
		);
		return $saida;
	}
	
	if (strpos($msg, 'UpdateCallDuration: ') === 0)
	{
		list($Uniqueid1, $Uniqueid2, $Seconds) = explode(':::', substr($msg, strlen('UpdateCallDuration: ')));
		$saida = array
		(
			'Action'    => 'UpdateCallDuration',
			'Uniqueid1' => $Uniqueid1,
			'Uniqueid2' => $Uniqueid2,
			'Seconds'   => $Seconds
		);
		return $saida;
	}
	
	return $json->encode(array('Action' => 'None', 'Message' => $msg));
}

session_start();
$sessid = session_id();
session_write_close();

$inicio = time();

$errno  = null;
$errstr = null;
$fp     = @fsockopen(HOSTNAME, HOSTPORT, $errno, $errstr, 60);

if ($errstr)
{
    $error  = "!! MonAst ERROR !!\n\nCould not connect to " . HOSTNAME . ":" . HOSTPORT . ": $errstr\n";
    $error .= "Make sure monast.py is running so the panel can connect to its port properly.";
	echo $json->encode(array(array('Action' => 'Error', 'Message' => $error)));
	die;
}

fwrite($fp, "SESSION: $sessid\r\n");

$isStatus = false;
$isOK     = false;
$events   = array();
$complete = false;

while (!feof($fp))
{
	if ($isOK && !$isStatus && !$complete)
	{
		fwrite($fp, "GET CHANGES\r\n");
		sleep(1);
	}
	
	$messages = fread($fp, 1024 * 64);
	$messages = explode("\r\n", $messages);
	
	foreach ($messages as $idx => $message)
	{
		if ($message)
		{
			if ($message == "OK")
			{
				fwrite($fp, "GET CHANGES\r\n");
				$isOK = true;
			}
			elseif ($message == "BEGIN CHANGES")
			{
				$isStatus = true;
			}
			elseif ($message == "END CHANGES")
			{
				$isStatus = false;
				if (count($events) > 0)
					$complete = true;
			}
			elseif ($isStatus)
			{
				$events[] = parseMsg($message);
			}
		}
	}
	
	session_start();
	$actions = getValor('Actions', 'session');
	if (count($actions) > 0)
	{
	    foreach ($actions as $action)
	    {
	        fwrite($fp, "$action\r\n");
	    }
	    setValor('Actions', array());
	}
	session_write_close();
	
	$now = time();
	if ($now >= ($inicio + MONAST_SOCKET_DURATION) && !$isStatus && $isOK)
	{
		$isOK = false;
		fwrite($fp, "BYE\r\n");
		
		if ($now >= (getValor('started', 'session') + MONAST_BROWSER_REFRESH))
		{
			$events[] = array('Action' => 'Reload', 'time' => '5000');
		}
		break;
	}
	
	if ($complete)
	{
		fwrite($fp, "BYE\r\n");
		break;
	}
}

fclose($fp);

echo $json->encode($events);

?>
