<?php
/**
 * Check multiple consecutive newlines in a file.
 */

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class MultipleEmptyLinesSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [
			T_WHITESPACE
		];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void|int
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// This sniff intentionally doesn't care about whitespace at the end of the file
		if ( !isset( $tokens[$stackPtr + 3] ) ) {
			return $phpcsFile->numTokens;
		}

		// For single line comment token or the php open tag the newline is part of the token
		// To detect a sequence of newlines after the comment start sequence on previous token
		$sequenceStartPtr = $stackPtr;
		if ( $tokens[$stackPtr - 1]['code'] === T_OPEN_TAG ||
			( $tokens[$stackPtr - 1]['code'] === T_COMMENT &&
			substr( $tokens[$stackPtr - 1]['content'], -2 ) !== '*/' )
		) {
			$sequenceStartPtr--;
		} else {
			// Otherwise the code must have at least 3 newlines in a row for a match
			if ( $tokens[$stackPtr + 2]['code'] !== T_WHITESPACE ||
				$tokens[$stackPtr + 2]['line'] === $tokens[$stackPtr + 3]['line']
			) {
				// There might be another sequence of newlines after this non-newline token
				return $stackPtr + 3;
			}
		}

		if ( $tokens[$stackPtr + 1]['code'] !== T_WHITESPACE ||
			$tokens[$stackPtr + 1]['line'] === $tokens[$stackPtr + 2]['line']
		) {
			// There might be another sequence of newlines after this non-newline token
			return $stackPtr + 2;
		}

		// Can only happen if there is whitespace at the end of a line, followed by 2 newlines
		if ( $tokens[$stackPtr]['line'] === $tokens[$stackPtr + 1]['line'] ) {
			return;
		}

		// We know we found 3 newlines already, no need to check these again
		$next = $sequenceStartPtr + 3;
		while ( isset( $tokens[$next + 1] ) &&
			$tokens[$next]['code'] === T_WHITESPACE &&
			$tokens[$next]['line'] !== $tokens[$next + 1]['line']
		) {
			$next++;
		}

		if ( $phpcsFile->addFixableError(
			'Multiple empty lines should not exist in a row; found %s consecutive empty lines',
			$sequenceStartPtr + 1,
			'MultipleEmptyLines',
			[ $next - $sequenceStartPtr - 1 ]
		) ) {
			$phpcsFile->fixer->beginChangeset();
			for ( $i = $sequenceStartPtr + 2; $i < $next; $i++ ) {
				$phpcsFile->fixer->replaceToken( $i, '' );
			}
			$phpcsFile->fixer->endChangeset();
		}

		return $next;
	}
}
