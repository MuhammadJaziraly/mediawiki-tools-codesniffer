<?php

/**
 * @return void
 */
function wfFailedExamples() {
	/*** This should fail */
	/*** This should fail */
	/* This also should fail **/
}

/**
 * @return void
 */
function wfPassedExamples() {
	/* Correct inline comment */
	/** This is valid */
	/** @var This is valid */
}
