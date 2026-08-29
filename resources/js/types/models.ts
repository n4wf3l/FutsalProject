export interface Team {
    id: number;
    name: string;
    logo_path: string | null;
    points?: number;
    wins?: number;
    draws?: number;
    losses?: number;
    goals_for?: number;
    goals_against?: number;
    goal_difference?: number;
    games_played?: number;
}

export interface Game {
    id: number;
    home_team_id: number;
    away_team_id: number;
    home_score: number | null;
    away_score: number | null;
    match_date: string;
    homeTeam?: Team;
    awayTeam?: Team;
    updatedBy?: { id: number; name: string } | null;
    updated_at?: string;
}

export interface Article {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    image: string | null;
    user_id?: number | null;
    created_at: string;
}

export interface Video {
    id: number;
    title: string;
    description: string | null;
    url: string;
    image: string | null;
    created_at: string;
}

export interface Photo {
    id: number;
    image: string;
    caption: string | null;
    gallery_id?: number;
}

export interface Gallery {
    id: number;
    name: string;
    description: string | null;
    cover_image: string | null;
    photos?: Photo[];
    created_at?: string;
}

export interface Player {
    id: number;
    first_name: string;
    last_name: string;
    photo: string | null;
    birthdate: string;
    position: string;
    number: number;
    nationality: string;
    height: number;
    contract_until: string;
}

export interface Coach {
    id: number;
    first_name: string;
    last_name: string;
    birth_date: string | null;
    coaching_since: string | null;
    birth_city: string | null;
    nationality: string | null;
    description: string | null;
    photo: string | null;
}

export interface Staff {
    id: number;
    first_name: string;
    last_name: string;
    position: string;
    photo: string | null;
}

export interface Tribune {
    id: number;
    name: string;
    description: string | null;
    price: number;
    currency: string;
    photo: string | null;
    available_seats: number;
}

export interface Championship {
    id: number;
    name: string;
    season: string;
}

export interface Regulation {
    id: number;
    title: string;
    pdf_path: string;
}

export interface AboutSection {
    id: number;
    title: string;
    content: string;
}

export interface PlayerApplication {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    birthdate: string;
    nationality: string | null;
    city: string | null;
    category: string;
    position_preference: string | null;
    current_club: string | null;
    experience_years: number | null;
    message: string | null;
    cv_path: string | null;
    status: string;
    reviewed_by_user_id: number | null;
    reviewed_at: string | null;
    admin_notes: string | null;
    reviewer?: { id: number; name: string } | null;
    created_at: string;
    updated_at: string;
}

export interface Interview {
    id: number;
    title: string;
    slug: string;
    interviewee_name: string;
    interviewee_role: string;
    interviewee_affiliation: string | null;
    hero_image: string | null;
    interviewee_photo: string | null;
    video_url: string | null;
    excerpt: string | null;
    quote_highlight: string | null;
    content: string;
    user_id: number | null;
    published_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface FlashMessage {
    id: number;
    message: string;
    homemessage: string | null;
}

export interface WelcomeImage {
    id: number;
    image_path: string;
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: Array<{ url: string | null; label: string; active: boolean }>;
}

export interface ClubInfoShared {
    name: string;
    prefix: string;
    city: string;
    location: string;
    phone: string | null;
    email: string;
    president: string | null;
    facebook: string | null;
    instagram: string | null;
    latitude: number | null;
    longitude: number | null;
}
