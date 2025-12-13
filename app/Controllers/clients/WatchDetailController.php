<?php
class WatchDetailController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $episodesModel;
    private $personModel;
    private $commentsModel;
    public function __construct()
    {
        $this->moviesModel = new Movies();
        $this->genresModel = new Genres();
        $this->episodesModel = new Episode();
        $this->personModel = new Person();
        $this->commentsModel = new Comments();
    }

    // Hàm đệ quy để tạo cây thư mục comment
    private function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                // Gọi lại chính nó để tìm con của element này
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['replies'] = $children;
                } else {
                    $element['replies'] = [];
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function showWatch()
    {
        $filter = filterData();
        $idMovie = $filter['id'];

        // Lấy thông tin phim chi tiết
        $condition = 'id=' . $idMovie;
        $movieDetail = $this->moviesModel->getMovieDetail($condition);

        // Lấy ID user đang đăng nhập
        if (isset($_SESSION['auth']['id'])) {
            $currentUserId = $_SESSION['auth']['id'];
        } else {
            $currentUserId = 0;
        }

        // Lấy thông tin tập phim
        $episodeDetail = [];
        $currentSeasonId = 0;

        // Lấy thông tin season
        $conditionSeason = 'movie_id=' . $idMovie;
        $seasonDetail = $this->moviesModel->getSeasonDetail($conditionSeason);

        if ($movieDetail['type_id'] == 2) {
            if (!empty($seasonDetail) && is_array($seasonDetail)) {

                $firstSeason = $seasonDetail[0];

                $currentSeasonId = $firstSeason['id'];

                $conditionEpisode = 'season_id=' . $currentSeasonId;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
            } else {
                $episodeDetail = $this->moviesModel->getAll("SELECT * 
                FROM episodes 
                WHERE movie_id = $idMovie 
                ORDER BY id ASC");
            }
        } else {
            $sourceInfo = $this->moviesModel->getVideoSources($idMovie);

            if (!empty($sourceInfo)) {
                $episodeDetail[] = [
                    'id' => $sourceInfo['id'],
                    'name' => $sourceInfo['voice_type'],
                    'link' => $sourceInfo['source_url'],
                ];
            }
        }

        // Lấy danh sách bình luận
        $comments = $this->commentsModel->getCommentsByMovie($idMovie, $currentUserId);
        // Xử lý Phân cấp Cha - Con (Tree Structure)
        $commentsTree = $this->buildTree($comments, 0);
        // ĐẾM SỐ LƯỢNG COMMENT CHA 
        $totalComments = count($commentsTree);

        // Lấy phim tương tự
        $similarMovies = $this->moviesModel->getSimilarMovies($idMovie, 12);

        // Lấy thông tin diễn viên
        $getCastByMovieId = $this->personModel->getCastByMovieId($idMovie);

        $data = [
            'idMovie' => $idMovie,
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
            'listComments' => $commentsTree,
            'totalComments' => $totalComments,
            'similarMovies' => $similarMovies

        ];
        $this->renderView('layout-part/client/watch', $data);
    }
}
