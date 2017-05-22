<?php
/**
 * Disallow empty line at the begin of function.
 */

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DisallowEmptyLineFunctionsSniff implements Sniff {

	/**
	 * @return array
	 */
	public function register() {
		return [
			T_FUNCTION
		];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$current = $tokens[$stackPtr];
		if ( isset( $current['scope_opener'] ) === false ||
			isset( $current['parenthesis_closer'] ) === false
		) {
			return;
		}
		$openBrace = $current['scope_opener'];
		$next = $phpcsFile->findNext( T_WHITESPACE, $openBrace + 1, null, true );
		if ( $next === false ) {
			return;
		}
		if ( $tokens[$next]['line'] > ( $tokens[$openBrace]['line'] + 1 ) ) {
			$error = 'Unexpected empty line at the begin of function.';
			$fix = $phpcsFile->addFixableError(
				$error,
				$stackPtr,
				'NoEmptyLine'
			);
			if ( $fix === true ) {
				$phpcsFile->fixer->beginChangeset();
				$i = $openBrace + 1;
				while ( $tokens[$i]['line'] !== $tokens[$next]['line'] ) {
					$phpcsFile->fixer->replaceToken( $i, '' );
					$i++;
				}
				$phpcsFile->fixer->addNewlineBefore( $i );
				$phpcsFile->fixer->endChangeset();
			}
		}
	}
}
