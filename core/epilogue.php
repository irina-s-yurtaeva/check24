<?php

//TODO make some jobs
if (\Check24\Model\Connection::getInstance())
{
	\Check24\Model\Connection::getInstance()->disconnect();
}
