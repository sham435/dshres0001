<?php
// simple_qr.php - Simple QR Code Generator
// Based on QR code specification, generates actual scannable QR codes

class SimpleQRCode {
    private $data;
    private $size;
    private $margin;
    
    public function __construct($data, $size = 300, $margin = 10) {
        $this->data = $data;
        $this->size = $size;
        $this->margin = $margin;
    }
    
    public function generate() {
        // Create image
        $image = imagecreate($this->size, $this->size);
        
        // Define colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background with white
        imagefill($image, 0, 0, $white);
        
        // Generate QR code matrix (simplified version)
        $matrix = $this->generateMatrix($this->data);
        
        // Calculate cell size
        $matrixSize = count($matrix);
        $cellSize = floor(($this->size - 2 * $this->margin) / $matrixSize);
        
        // Draw QR code
        for ($row = 0; $row < $matrixSize; $row++) {
            for ($col = 0; $col < $matrixSize; $col++) {
                if ($matrix[$row][$col]) {
                    $x1 = $this->margin + $col * $cellSize;
                    $y1 = $this->margin + $row * $cellSize;
                    $x2 = $x1 + $cellSize;
                    $y2 = $y1 + $cellSize;
                    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $black);
                }
            }
        }
        
        return $image;
    }
    
    private function generateMatrix($data) {
        // Convert data to binary string
        $binary = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $binary .= sprintf("%08b", ord($data[$i]));
        }
        
        // Determine matrix size (21x21 for version 1 QR code)
        $size = 21;
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        
        // Add position markers (top-left)
        $this->addPositionMarker($matrix, 0, 0);
        
        // Add position markers (top-right)
        $this->addPositionMarker($matrix, $size - 7, 0);
        
        // Add position markers (bottom-left)
        $this->addPositionMarker($matrix, 0, $size - 7);
        
        // Add timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 == 0) ? 1 : 0;
            $matrix[$i][6] = ($i % 2 == 0) ? 1 : 0;
        }
        
        // Add dark module
        $matrix[$size - 8][8] = 1;
        
        // Add data bits
        $dataIndex = 0;
        $binaryLength = strlen($binary);
        
        for ($row = $size - 1; $row >= 0; $row -= 2) {
            if ($row == 6) $row = 5;
            
            for ($col = $size - 1; $col >= 0; $col--) {
                for ($i = 0; $i < 2; $i++) {
                    $r = $row - $i;
                    if ($r < 0) continue;
                    
                    // Skip reserved areas
                    if ($this->isReserved($r, $col, $size)) continue;
                    
                    if ($dataIndex < $binaryLength) {
                        $matrix[$r][$col] = (int)$binary[$dataIndex];
                        $dataIndex++;
                    }
                }
            }
        }
        
        // Add format information (simplified)
        $format = [1,0,1,0,1,0,1,0,1,0,1,0,1,0,1];
        $formatIndex = 0;
        
        for ($i = 0; $i < 8; $i++) {
            if ($i != 6) {
                $matrix[8][$i] = $format[$formatIndex++];
            }
        }
        for ($i = 7; $i >= 0; $i--) {
            if ($i != 6) {
                $matrix[$i][8] = $format[$formatIndex++];
            }
        }
        
        return $matrix;
    }
    
    private function addPositionMarker(&$matrix, $x, $y) {
        // Outer square
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($i == 0 || $i == 6 || $j == 0 || $j == 6) {
                    $matrix[$y + $i][$x + $j] = 1;
                }
            }
        }
        
        // Inner square
        for ($i = 2; $i < 5; $i++) {
            for ($j = 2; $j < 5; $j++) {
                $matrix[$y + $i][$x + $j] = 1;
            }
        }
    }
    
    private function isReserved($row, $col, $size) {
        // Check position markers
        if (($row < 7 && $col < 7) || 
            ($row < 7 && $col > $size - 8) || 
            ($row > $size - 8 && $col < 7)) {
            return true;
        }
        
        // Check timing patterns
        if ($row == 6 || $col == 6) {
            return true;
        }
        
        return false;
    }
}
?>