<?php
@session_start();  // Start session before any output

include_once("path.php");
include_once(ROOT_PATH . '/app/database/db.php');
include_once(ROOT_PATH . '/app/controllers/posts.php');

// Handle comment submission before outputting HTML
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_SESSION['id'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['id'];
    $post_id = $_GET['id'];
    
    $sql = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $user_id, $post_id, $comment);
    $stmt->execute();

    // Redirect to prevent duplicate submission on refresh
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Handle comment deletion
if (isset($_POST['delete_comment']) && isset($_SESSION['id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['id'];

    $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $comment_id, $user_id);
    $stmt->execute();

    // Redirect to the same page to see the changes
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Handle comment editing
if (isset($_POST['edit_comment']) && isset($_SESSION['id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['id'];
    $new_content = htmlspecialchars($_POST['new_content']);

    $sql = "UPDATE comments SET content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $new_content, $comment_id, $user_id);
    $stmt->execute();

    // Redirect to the same page to see the changes
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Fetch post details
if (isset($_GET['id'])) {
    $post = selectOne('posts', ['id' => $_GET['id']]);
}
$topics = selectAll('topics');
$posts = selectAll('posts', ['published' => 1]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Candal|Lora" rel="stylesheet">

  <!-- Custom Styling -->
  <link rel="stylesheet" href="assets/css/style.css">

  <title><?php echo $post['title']; ?> | AwaInspires</title>
  
</head>

<body>
  <!-- Facebook Page Plugin SDK -->
  <div id="fb-root"></div>
  <script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=285071545181837&autoLogAppEvents=1">
  </script>

  <?php include(ROOT_PATH . "/app/includes/header.php"); ?>

  <!-- Page Wrapper -->
  <div class="page-wrapper">

    <!-- Content -->
    <div class="content clearfix">

      <!-- Main Content Wrapper -->
      <div class="main-content-wrapper">
        <div class="main-content single">
          <h1 class="post-title"><?php echo $post['title']; ?></h1>

          <div class="post-content">
            <?php echo html_entity_decode($post['body']); ?>
          </div>
          
          <?php
          // Include database connection
          include('app/database/db.php');

          // Get post ID from URL
          if (!isset($_GET['id']) || empty($_GET['id'])) {
              die("Post ID is missing!");
          }

          $post_id = $_GET['id'];
          $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

          // Prepare SQL query to fetch comments
          $sql = "SELECT comments.id, comments.content, comments.created_at, users.username, comments.user_id 
                  FROM comments 
                  JOIN users ON comments.user_id = users.id 
                  WHERE comments.post_id = ? 
                  ORDER BY comments.created_at DESC";

          $stmt = $conn->prepare($sql);
          $stmt->bind_param('i', $post_id);
          $stmt->execute();
          $result = $stmt->get_result();

          // Display comments
          echo "<div class='comments-list'><h3>Comments</h3>";

          // If no comments found
          if ($result->num_rows == 0) {
              echo "<p>No comments found.</p>";
          } else {
              while ($row = $result->fetch_assoc()) {
                  echo "<div class='comment'><strong>" . htmlspecialchars($row['username']) . ":</strong> ";
                  // Edit Mode
                  if ($user_id == $row['user_id'] && isset($_POST['edit_mode']) && $_POST['comment_id'] == $row['id']) {
                      echo "<form method='POST' action='single.php?id=$post_id' style='display:inline;'>
                              <textarea name='new_content' rows='2' required>".htmlspecialchars($row['content'])."</textarea>
                              <input type='hidden' name='comment_id' value='{$row['id']}'>
                              <button type='submit' name='edit_comment'>Save</button>
                            </form>";
                  } else {
                      echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                      echo "<small>Posted on: " . $row['created_at'] . "</small>";

                      // Show Edit/Delete buttons for the comment owner
                      if ($user_id == $row['user_id']) {
                          echo "<form method='POST' action='single.php?id=$post_id' style='display:inline;'>
                                  <input type='hidden' name='comment_id' value='{$row['id']}'>
                                  <button type='submit' name='edit_mode'>Edit</button>
                                </form>";
                          echo "<form method='POST' action='single.php?id=$post_id' style='display:inline;'>
                                  <input type='hidden' name='comment_id' value='{$row['id']}'>
                                  <button type='submit' name='delete_comment'>Delete</button>
                                </form>";
                      }
                  }
                  echo "<hr></div>";
              }
          }
          echo "</div>";
          ?>

          <?php if (isset($_SESSION['id'])): ?>
              <div class="comment-section">
                  <h3>Leave a Comment</h3>
                  <form action="single.php?id=<?php echo $_GET['id']; ?>" method="POST">
                      <textarea name="comment" rows="4" required></textarea>
                      <button type="submit">Submit</button>
                  </form>
              </div>
          <?php else: ?>
              <p>You must <a href="login.php">log in</a> to leave a comment.</p>
          <?php endif; ?>
          
        </div>
      </div>
      <!-- // Main Content -->

      <!-- Sidebar -->
      <div class="sidebar single">

        <div class="fb-page" data-href="https://web.facebook.com/codingpoets/" data-small-header="false"
          data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
          <blockquote cite="https://web.facebook.com/codingpoets/" class="fb-xfbml-parse-ignore"><a
              href="https://web.facebook.com/codingpoets/">Coding Poets</a></blockquote>
        </div>


        <div class="section popular">
          <h2 class="section-title">Popular</h2>

          <?php foreach ($posts as $p): ?>
            <div class="post clearfix">
              <img src="<?php echo BASE_URL . '/assets/images/' . $p['image']; ?>" alt="">
              <a href="" class="title">
                <h4><?php echo $p['title'] ?></h4>
              </a>
            </div>
          <?php endforeach; ?>
          

        </div>

        <div class="section topics">
          <h2 class="section-title">Topics</h2>
          <ul>
            <?php foreach ($topics as $topic): ?>
              <li><a href="<?php echo BASE_URL . '/index.php?t_id=' . $topic['id'] . '&name=' . $topic['name'] ?>"><?php echo $topic['name']; ?></a></li>
            <?php endforeach; ?>

          </ul>
        </div>
      </div>
      <!-- // Sidebar -->

    </div>
    <!-- // Content -->

  </div>
  <!-- // Page Wrapper -->

  <?php include(ROOT_PATH . "/app/includes/footer.php"); ?>


  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Slick Carousel -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <!-- Custom Script -->
  <script src="assets/js/scripts.js"></script>

</body>

</html>