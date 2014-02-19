<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// add TypoScript for the asset serving
// todo: move typeNum to extension configuration
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('fal_securedownload', 'setup',
	'
	FalSecuredownload = PAGE
	FalSecuredownload {
		typeNum = 1337
		config {
			disableAllHeaderCode = 1
			admPanel = 0
		}
		10 = USER
		10.userFunc = BeechIt\FalSecuredownload\Resource\FileDelivery->deliver
	}
');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BeechIt.' . $_EXTKEY,
	'Filetree',
	array(
		'FileTree' => 'tree',
	),
	// non-cacheable actions
	array(
	)
);

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
	'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
	\TYPO3\CMS\Core\Resource\ResourceStorage::SIGNAL_PreGeneratePublicUrl,
	'BeechIt\\FalSecuredownload\\Security\\PublicUrlAspect',
	'generatePublicUrl'
);