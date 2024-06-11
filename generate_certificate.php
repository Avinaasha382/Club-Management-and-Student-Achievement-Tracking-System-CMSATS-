<?php
// Validate and sanitize input data
$user_name = isset($_GET['user_name']) ? htmlspecialchars($_GET['user_name']) : '';
$event_name = isset($_GET['event_name']) ? htmlspecialchars($_GET['event_name']) : '';

// Check if user and event names are provided
#if (empty($user_name) || empty($event_name)) {
#    // Handle error: Invalid input data
#    exit('Invalid input data');
#}

// LaTeX template content
$current_date = date('F j, Y');
$latex_content = '\documentclass{article}
\usepackage[utf8]{inputenc}
\usepackage[T1]{fontenc}
\usepackage{calligra}

\author{}
\date{}

\begin{document}

{\centering\fontsize{50}{60}\selectfont\calligra\textbf{Certificate of Participation}\par}

\vspace{1cm}

{\centering\LARGE This certificate is proudly presented to\\
\Huge \textbf{[USER_NAME]}\par}

\vspace{0.5cm}
{\centering\Large for participating in the \textbf{[EVENT_NAME]} event.\par}

\vspace{2cm}

\Large Date: [CURRENT_DATE]

\vspace{1cm}

Signature:

\end{document}';

// Replace placeholders with user and event names
$latex_content = str_replace('[USER_NAME]', $user_name, $latex_content);
$latex_content = str_replace('[EVENT_NAME]', $event_name, $latex_content);
$latex_content = str_replace('[CURRENT_DATE]', $current_date, $latex_content);

// Write modified LaTeX content to a temporary file
$temp_latex_file = tempnam(sys_get_temp_dir(), 'certificate');
file_put_contents($temp_latex_file . '.tex', $latex_content);

// Compile LaTeX document to PDF using pdflatex
$output = shell_exec("pdflatex -interaction=nonstopmode -output-directory=" . escapeshellarg(sys_get_temp_dir()) . " " . escapeshellarg($temp_latex_file . '.tex'));

// Check if PDF generation was successful
if (!file_exists($temp_latex_file . '.pdf')) {
    // Handle error: PDF generation failed
    exit('PDF generation failed');
}

// Set headers to force download the generated PDF file
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="certificate.pdf"');
header('Content-Length: ' . filesize($temp_latex_file . '.pdf'));
readfile($temp_latex_file . '.pdf');

// Clean up: Delete temporary LaTeX and PDF files
unlink($temp_latex_file . '.tex');
unlink($temp_latex_file . '.pdf');
?>
