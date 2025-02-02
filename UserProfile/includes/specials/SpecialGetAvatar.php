<?php
/**
 * A special page that redirects to user's avatar.
 *
 * @ingroup SpecialPage
 * @since 1.22
 */
class SpecialGetAvatar extends FormSpecialPage {
	public function __construct() {
		parent::__construct( 'GetAvatar' );
	}
	public function setParameter( $subpage ) {
		$parts = explode( '/', $subpage, 2 );
		$username = $parts[0];
		$size = $parts[1] ?? 'm';
		$this->onSubmit([ 'username' => $username, 'size' => $size ]);
	}
	public function onSubmit( array $data ) {
		$username = '';
		$size = 'm';
		if ( isset( $data['username'] ) && $data['username'] )
			$username = $data['username'];
		if ( isset( $data['size'] ) && $data['size'] )
			$size = $data['size'];
		$user = User::newFromName( $username );
		if ( $user instanceof User ) {
			$id = $user->getId();
			$avatar = new wAvatar( $id, $size );
		} else {
			// Fallback for the case where an invalid (nonexistent)
			// user name was supplied...
			// not very nice, but -1 will get the default avatar
			$avatar = new wAvatar( -1, $size );
		}
		$this->getOutput()->setCdnMaxage( 60 * 60 );
		$this->getOutput()->redirect( $avatar->getAvatarUrlPath(), 302 );
		return true;
	}

	public function onSuccess() {
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * @return bool
	 */
	public function requiresWrite() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'redirects';
	}
	protected function getFormFields() {
		return array();
	}
}
