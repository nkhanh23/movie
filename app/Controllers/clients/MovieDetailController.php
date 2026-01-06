<?php

class MovieDetailController extends baseController
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

    public function showDetail($slug)
    {

        $filter = filterData();
        $idEpisode = $filter['episode_id'] ?? null;

        //Tìm phim dựa trên Slug
        $movie = $this->moviesModel->findBySlug($slug);
        if (!$movie) {
            echo "Phim không tồn tại!";
            return;
        }
        $idMovie = $movie['id'];
        // Lấy ID user
        if (isset($_SESSION['auth']['id'])) {
            $currentUserId = $_SESSION['auth']['id'];
        } else {
            $currentUserId = 0;
        }

        // 1. Lấy thông tin phim
        $condition = 'm.id=' . $idMovie;
        $movieDetail = $this->moviesModel->getMovieDetail($condition);

        // Lấy thông tin season
        $conditionSeason = 'movie_id=' . $idMovie;
        $seasonDetail = $this->moviesModel->getSeasonDetail($conditionSeason);

        // Lấy thông tin tập phim
        $episodeDetail = [];
        $currentSeasonId = 0;

        // CHECK LOẠI PHIM
        // type_id = 2 là Phim Bộ (Series)
        if ($movieDetail['type_id'] == 2) {
            if (!empty($seasonDetail) && is_array($seasonDetail)) {
                $firstSeason = $seasonDetail[0];
                $currentSeasonId = $firstSeason['id'];
                $conditionEpisode = 'season_id=' . $currentSeasonId;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
            } else {
                // Phim bộ không có season -> lấy theo movie_id
                $conditionEpisode = 'movie_id=' . $idMovie;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
            }
        } else {
            // --- LOGIC PHIM LẺ --

            $sourceInfo = $this->moviesModel->getSingleMovieSource($idMovie);

            if (!empty($sourceInfo)) {
                $episodeDetail[] = [
                    'id' => $sourceInfo['episode_id'],
                    'name' => $sourceInfo['voice_type'] ?? 'Vietsub',
                    'link' => $sourceInfo['source_url'],
                    'source_name' => $sourceInfo['source_name'] ?? 'Server 1',
                    'voice_type' => $sourceInfo['voice_type'] ?? 'Vietsub',
                ];
            } else {
                // Trường hợp dự phòng: Nếu chưa crawl được link
                $episodeDetail = [];
            }
        }



        $getCastByMovieId = $this->personModel->getCastByMovieId($idMovie);
        $comments = $this->commentsModel->getCommentsByMovie($idMovie, $currentUserId);
        $commentsTree = $this->buildTree($comments, 0);
        $totalComments = count($commentsTree);
        $similarMovies = $this->moviesModel->getSimilarMovies($idMovie, 12);

        $movieIsFavorited = false;
        if (!empty($_SESSION['auth'])) {
            $userId = $_SESSION['auth']['id'];
            $checkFavorite = $this->moviesModel->checkIsFavorite($userId, $idMovie);
            $movieIsFavorited = !empty($checkFavorite);
        }

        $countAllCommentsByMovie = $this->commentsModel->countCommentsByMovie($idMovie);

        // SEO Data
        $genreNames = !empty($movieDetail['genre_name']) ? $movieDetail['genre_name'] : '';
        $seoTitle = $movieDetail['tittle'] . ' - Xem phim ' . $movieDetail['original_tittle'] . ' | Phê Phim';
        $seoDescription = 'Xem phim ' . $movieDetail['tittle'] . ' (' . $movieDetail['original_tittle'] . ') vietsub HD. ' .
            (!empty($movieDetail['description']) ? mb_substr(strip_tags($movieDetail['description']), 0, 150) . '...' :
                'Phim ' . $genreNames . ' hay nhất tại Phê Phim.');
        $seoKeywords = $movieDetail['tittle'] . ', ' . $movieDetail['original_tittle'] . ', xem phim ' . $movieDetail['tittle'] .
            ', ' . $genreNames . ', phim hd, phim vietsub';
        $seoImage = $movieDetail['poster_url'] ?? '';
        $seoCanonical = _HOST_URL . '/phim/' . $slug;

        $data = [
            'idMovie' => $idMovie,
            'idEpisode' => $idEpisode,
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'currentSeasonNumber' => (!empty($seasonDetail)) ? 1 : null, // Season đầu tiên = 1
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
            'listComments' => $commentsTree,
            'totalComments' => $totalComments,
            'countAllCommentsByMovie' => $countAllCommentsByMovie[0]['total'],
            'similarMovies' => $similarMovies,
            'movieIsFavorited' => $movieIsFavorited,
            // SEO
            'seoTitle' => $seoTitle,
            'seoDescription' => $seoDescription,
            'seoKeywords' => $seoKeywords,
            'seoImage' => $seoImage,
            'seoCanonical' => $seoCanonical
        ];

        $this->renderView('layout-part/client/detail', $data);
    }

    // API Lấy danh sách tập
    public function getEpisodesApi()
    {
        $filter = filterData('get');
        $seasonId = $filter['season_id'];

        // Gọi Model lấy danh sách tập
        $condition = 'season_id=' . $seasonId;
        $episodes = $this->moviesModel->getEpisodeDetail($condition);

        // Trả về kết quả dạng JSON
        header('Content-Type: application/json');
        echo json_encode($episodes);
        exit; // Dừng chương trình để không load thêm layout
    }
}
