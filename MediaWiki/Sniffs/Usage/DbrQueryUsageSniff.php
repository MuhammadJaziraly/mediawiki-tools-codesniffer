<?php
/**
 * Report warnings when $dbr->query() is used instead of $dbr->select()
 *
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DbrQueryUsageSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [ T_OBJECT_OPERATOR ];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$dbrPtr = $phpcsFile->findPrevious( T_VARIABLE, $stackPtr );
		$methodPtr = $phpcsFile->findNext( T_STRING, $stackPtr );

		if ( $tokens[$dbrPtr]['content'] === '$dbr'
			&& $tokens[$methodPtr]['content'] === 'query'
		) {
			$phpcsFile->addWarning(
				'Call $dbr->select() wrapper instead of $dbr->query()',
				$stackPtr,
				'DbrQueryFound'
			);
		}
	}
}
