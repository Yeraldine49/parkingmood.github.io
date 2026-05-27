<!DOCTYPE html>
require_once 'config/database.html';
session_destroy();
header('Location: login.php');
exit();
?>
