<?php

namespace SecurityTxt;

class Parser {
	const FIELD_CANONICAL = 'canonical';
	const FIELD_CONTACT = 'contact';
	const FIELD_ENCRYPTION = 'encryption';
	const FIELD_POLICY = 'policy';
	const FIELD_ACKNOWLEDGEMENT = 'acknowledgements';
	const FIELD_LANGUAGE = 'preferred-languages';
	const FIELD_HIRING = 'hiring';
	
	private $errors = [];
	private $comments = [];
	
	private $fields = [
		self::FIELD_CANONICAL       => [],
		self::FIELD_CONTACT         => [],
		self::FIELD_ENCRYPTION      => [],
		self::FIELD_POLICY          => [],
		self::FIELD_ACKNOWLEDGEMENT => [],
		self::FIELD_LANGUAGE        => [],
		self::FIELD_HIRING          => [],
	];
	
	public function __construct( $raw = "" ) {
		if ( $raw ) {
			$this->parse( $raw );
		}
	}
	
	public function parse( $raw ) {
		$lines = explode( "\n", $raw );
		
		if ( sizeOf( $lines ) < 1 ) {
			$this->addError( "empty file" );
			
			return false;
		}
		
		$n = 0;
		foreach ( $lines as $line ) {
			$n ++;
			
			// Empty line
			$line = trim( $line );
			if ( ! $line ) {
				continue;
			}
			
			// Comment
			if ( $line[0] == "#" ) {
				$this->comments[] = $line;
				continue;
			}
			
			$parts = explode( ":", $line, 2 );
			if ( sizeOf( $parts ) != 2 ) {
				$this->addError( "invalid input on line {$n}: {$line}" );
				continue;
			}
			
			$option = mb_convert_case( $parts[0], MB_CASE_LOWER );
			$value  = trim( $parts[1] );
			
			if ( ! $this->validateField( $option, $value, $n ) ) {
				continue;
			}
			
			$this->fields[ $option ][] = $value;
		}
		
		if ( sizeOf( $this->fields[ self::FIELD_CONTACT ] ) < 1 ) {
			$this->addError( "does not contain at least one contact field" );
			
			return false;
		}
		
		return ! $this->hasErrors();
	}
	
	private function validateField( $option, $value, $lineNo = 0 ) {
		switch ( $option ) {
			case self::FIELD_CONTACT:
				return $this->validateContact( $option, $value, $lineNo );
			
			case self::FIELD_CANONICAL:
			case self::FIELD_ENCRYPTION:
			case self::FIELD_POLICY:
			case self::FIELD_ACKNOWLEDGEMENT:
			case self::FIELD_HIRING:
				return $this->validateUri( $option, $value, $lineNo );
			
			case self::FIELD_LANGUAGE:
				return $this->validateLanguage( $option, $value, $lineNo );
			
			default:
				$this->addError( "invalid option '{$option}' on line {$lineNo}" );
		}
		
		return false;
	}
	
	private function validateContact( $option, $value, $lineNo ) {
		$lower = mb_convert_case( $value, MB_CASE_LOWER );
		if ( ! (
			filter_var( $value, FILTER_VALIDATE_URL ) ||
			filter_var( $value, FILTER_VALIDATE_EMAIL ) ||
			$this->isValidPhoneNumber( $value )
		) ) {
			$this->addError( "invalid value '{$value}' for option '{$option}' on line {$lineNo}" );
			
			return false;
		}
		
		return true;
	}
	
	private function validateUri( $option, $value, $lineNo ) {
		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$this->addError( "invalid URI '{$value}' for option '{$option}' on line {$lineNo}" );
			
			return false;
		}
		
		return true;
	}
	
	private function validateLanguage( $option, $value, $lineNo ) {
		$lower = mb_convert_case( $value, MB_CASE_LOWER );
		// multiple languages allowed, code ISO 2 letters, comma separated
		$langs = explode( ',', $value );
		
		foreach ( $langs as $lang ):
			$lang = trim( $lang );
			
			if ( ! $this->isValidCodeIso( $lang ) ):
				$this->addError( "invalid value '{$lang}' for option '{$option}' on line {$lineNo}" );
				
				return false;
			endif;
		endforeach;
		
		return true;
	}
	
	private function isValidCodeIso( $candidate, $length = 2 ) {
		return ( preg_match( "/^[a-z]{{$length}}$/", $candidate ) > 0 );
	}
	
	private function isValidPhoneNumber( $candidate ) {
		return ( preg_match( "/^\+[0-9\(\) -]+$/", $candidate ) > 0 );
	}
	
	private function addError( $msg ) {
		$this->errors[] = $msg;
	}
	
	public function hasErrors() {
		return ( sizeOf( $this->errors ) > 0 );
	}
	
	public function errors() {
		return $this->errors;
	}
	
	public function hasComments() {
		return ( sizeOf( $this->comments ) > 0 );
	}
	
	public function comments() {
		return $this->comments;
	}
	
	public function hasCanonical() {
		return ( sizeOf( $this->fields[ self::FIELD_CANONICAL ] ) > 0 );
	}
	
	public function canonical() {
		return $this->fields[ self::FIELD_CANONICAL ];
	}
	
	public function hasContact() {
		return ( sizeOf( $this->fields[ self::FIELD_CONTACT ] ) > 0 );
	}
	
	public function contact() {
		return $this->fields[ self::FIELD_CONTACT ];
	}
	
	public function hasEncryption() {
		return ( sizeOf( $this->fields[ self::FIELD_ENCRYPTION ] ) > 0 );
	}
	
	public function encryption() {
		return $this->fields[ self::FIELD_ENCRYPTION ];
	}
	
	public function hasPolicy() {
		return ( sizeOf( $this->fields[ self::FIELD_POLICY ] ) > 0 );
	}
	
	public function policy() {
		return $this->fields[ self::FIELD_POLICY ];
	}
	
	public function hasAcknowledgement() {
		return ( sizeOf( $this->fields[ self::FIELD_ACKNOWLEDGEMENT ] ) > 0 );
	}
	
	public function acknowledgement() {
		return $this->fields[ self::FIELD_ACKNOWLEDGEMENT ];
	}
	
	public function fields() {
		return $this->fields;
	}
	
}
