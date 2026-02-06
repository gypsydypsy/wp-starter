<?php
require_once( dirname( dirname( __FILE__ ) ) . '/libs/tcpdf/tcpdf.php' );

class Audit_PDF extends TCPDF {
	
	/**
	 * ok score
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      int $ok_score Achievement ok
	 */
	private $ok_score;
	
	/**
	 * nok score
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      int $nok_score Achievement nok
	 */
	private $nok_score;
	
	//Page header
	public function Header() {
		$plugin_dir = dirname( dirname( __FILE__ ) );
		// Logo
		$image_file = $plugin_dir . '/admin/img/hdf-violet.png';
		$this->Image( $image_file, 3, 3, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false );
		// Set font
		$this->SetFont( 'helvetica', 'B', 20 );
		// Title
		$this->Cell( 0, 15, __( 'Audit' ) . ' - ' . get_bloginfo( 'name' ), 0, false, 'C', 0, '', 0, false, 'M', 'M' );
		// Add current date
		$this->Ln();
		$this->SetFont( 'helvetica', '', 8 );
		$this->Cell( 0, 10, current_time( 'Y-m-d H:i:s' ), 0, false, 'C', 0, '', 0, false, 'M', 'M' );
	}
	
	public function drawAuditCells( $title, $global_status, $checklist ) {
		if ( $global_status ):
			$global_status = 'ok';
		else:
			$global_status = 'nok';
		endif;
		
		$colors = array(
			'ok'  => array(
				'border'     => array( 214, 233, 198 ),
				'background' => array( 223, 240, 216 ),
				'text'       => array( 60, 118, 61 ),
			),
			'nok' => array(
				'border'     => array( 250, 235, 204 ),
				'background' => array( 252, 248, 227 ),
				'text'       => array( 138, 109, 59 ),
			),
		);
		
		$checklist_colors = array(
			'ok'  => array(
				'border'     => array( 214, 233, 198 ),
				'background' => array( 255, 255, 255 ),
				'text'       => array( 0, 0, 0 ),
			),
			'nok' => array(
				'border'     => array( 169, 68, 66 ),
				'background' => array( 242, 222, 222 ),
				'text'       => array( 169, 68, 66 ),
			),
		);
		
		$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $colors[ $global_status ]['border'] ) );
		call_user_func_array( array( $this, 'SetFillColor' ), $colors[ $global_status ]['background'] );
		call_user_func_array( array( $this, 'SetTextColor' ), $colors[ $global_status ]['text'] );
		$this->SetFont( 'helvetica', '', 12 );
		$this->Cell( 0, 0, $title, 1, 1, 'L', 1, 0 );
		
		foreach ( $checklist as $item ):
			switch ( $item['status'] ):
				case 'ok':
					$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $checklist_colors[ $item['status'] ]['border'] ) );
					call_user_func_array( array( $this, 'SetFillColor' ), $checklist_colors[ $item['status'] ]['background'] );
					call_user_func_array( array( $this, 'SetTextColor' ), $checklist_colors[ $item['status'] ]['text'] );
					$this->SetFont( 'helvetica', '', 8 );
					$this->Cell( 0, 0, $item['text'], 1, 1, 'L', 1, 0 );
					$this->Plus_ok();
					break;
				case 'nok':
					$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $checklist_colors[ $item['status'] ]['border'] ) );
					call_user_func_array( array( $this, 'SetFillColor' ), $checklist_colors[ $item['status'] ]['background'] );
					call_user_func_array( array( $this, 'SetTextColor' ), $checklist_colors[ $item['status'] ]['text'] );
					$this->SetFont( 'helvetica', '', 8 );
					$this->Cell( 0, 0, $item['text'], 1, 1, 'L', 1, 0 );
					$this->Plus_nok();
					break;
			endswitch;
		endforeach;
		
		$this->Ln();
	}
	
	public function drawAuditChecklistCells( $title, $id, $checklist, $guideline_checklist ) {
		$colors = array(
			'border'     => array( 212, 212, 212 ),
			'background' => array( 255, 255, 255 ),
			'text'       => array( 0, 0, 0 ),
		);
		
		$checklist_colors = array(
			'ok'  => array(
				'border'     => array( 214, 233, 198 ),
				'background' => array( 255, 255, 255 ),
				'text'       => array( 0, 0, 0 ),
			),
			'nok' => array(
				'border'     => array( 169, 68, 66 ),
				'background' => array( 242, 222, 222 ),
				'text'       => array( 169, 68, 66 ),
			),
		);
		
		$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $colors['border'] ) );
		call_user_func_array( array( $this, 'SetFillColor' ), $colors['background'] );
		call_user_func_array( array( $this, 'SetTextColor' ), $colors['text'] );
		$this->SetFont( 'helvetica', '', 12 );
		$this->Cell( 0, 0, $title, 1, 1, 'L', 1, 0 );
		
		foreach ( $checklist as $key => $item ):
			if ( isset( $item['value'] ) && ( 'ok' === $item['value'] ) ):
				$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $checklist_colors[ $item['value'] ]['border'] ) );
				call_user_func_array( array( $this, 'SetFillColor' ), $checklist_colors[ $item['value'] ]['background'] );
				call_user_func_array( array( $this, 'SetTextColor' ), $checklist_colors[ $item['value'] ]['text'] );
				$this->SetFont( 'helvetica', '', 8 );
				$html = stripslashes_deep( $guideline_checklist[ $id ]['checks'][ $key ]['text'] );
				//$this->Cell( 0, 0, $guideline_checklist[ $id ]['checks'][ $key ]['text'], 1, 1, 'L', 1, 0 );
				$this->Plus_ok();
			else:
				$this->SetLineStyle( array( 'width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $checklist_colors['nok']['border'] ) );
				call_user_func_array( array( $this, 'SetFillColor' ), $checklist_colors['nok']['background'] );
				call_user_func_array( array( $this, 'SetTextColor' ), $checklist_colors['nok']['text'] );
				$this->SetFont( 'helvetica', '', 8 );
				$html = stripslashes_deep( $guideline_checklist[ $id ]['checks'][ $key ]['text_nok'] );
				//$this->Cell( 0, 0, $guideline_checklist[ $id ]['checks'][ $key ]['text_nok'], 1, 1, 'L', 1, 0 );
				$this->Plus_nok();
			endif;
			
			if ( ! empty( $item['comments'] ) ):
				$html .= '<br/>' . nl2br( htmlspecialchars( stripslashes_deep( $item['comments'] ) ) );
			endif;
			
			$this->writeHTMLCell( 0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true );
		
		endforeach;
		
		$this->Ln();
	}
	
	private function Plus_ok() {
		$this->ok_score ++;
	}
	
	private function Plus_nok() {
		$this->nok_score ++;
	}
	
	public function Get_Score() {
		// calculate global score
		return floor( ( $this->ok_score * 100 ) / ( (int) $this->ok_score + (int) $this->nok_score ) );
	}
	
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY( - 15 );
		// Set font
		$this->SetFont( 'helvetica', 'I', 8 );
		// Page number
		$this->Cell( 0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M' );
	}
}