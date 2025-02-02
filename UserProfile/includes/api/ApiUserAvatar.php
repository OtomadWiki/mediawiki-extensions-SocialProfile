<?php
/**
 * API module for setting the type of user profile, i.e. should a social profile
 * page or the wikitext page be shown by default when [[User:Foo]] is accessed
 *
 * @file
 * @ingroup API
 * @license GPL-2.0-or-later
 */

class ApiUserAvatar extends ApiBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	/**
	 * Main entry point
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$username = "";
		if ( isset( $params['user'] ) ) {
			$username = $params['user'];
		}
		$user = User::newFromName( $username );
		if ( $user instanceof User ) {
			$id = $user->getId();
			$avatar = new wAvatar( $id, 'l' );
		} else {
			// Fallback for the case where an invalid (nonexistent)
			// user name was supplied...
			// not very nice, but -1 will get the default avatar
			$avatar = new wAvatar( -1, 'l' );
		}

		$output = $avatar->getAvatarUrlPath();
		$result = $this->getResult();
		$data = [
			'url' => $output
		];
		$result->addValue( null, $this->getModuleName(), $data );
	}

	public function isWriteMode() {
		return true;
	}

	/**
	 * @return array
	 */
	protected function getAllowedParams() {
		return [
			'user' => [
				ApiBase::PARAM_TYPE => 'string',
			],
		];
	}

}
