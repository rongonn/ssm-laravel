
-- SERVICES TABLE
CREATE TABLE services (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  duration TEXT DEFAULT '60 min',
  category TEXT CHECK (category IN ('Hair', 'Skin', 'Nails', 'Massage', 'Other')),
  image_url TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- PRODUCTS TABLE
CREATE TABLE products (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  brand TEXT,
  stock INTEGER DEFAULT 0,
  image_url TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  category TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- TEAM TABLE
CREATE TABLE team (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT NOT NULL,
  role TEXT NOT NULL,
  bio TEXT,
  image_url TEXT,
  specialty TEXT[],
  facebook_url TEXT,
  instagram_url TEXT,
  whatsapp_url TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- TESTIMONIALS TABLE
CREATE TABLE testimonials (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  author TEXT NOT NULL,
  content TEXT NOT NULL,
  rating INTEGER CHECK (rating >= 1 AND rating <= 5),
  avatar_url TEXT,
  date DATE DEFAULT CURRENT_DATE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- GALLERY TABLE
CREATE TABLE gallery (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  title TEXT,
  category TEXT,
  image_url TEXT NOT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- CONTACT FORM SUBMISSIONS
CREATE TABLE contacts (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  subject TEXT,
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- ENABLE RLS (Row Level Security)
ALTER TABLE services ENABLE ROW LEVEL SECURITY;
ALTER TABLE products ENABLE ROW LEVEL SECURITY;
ALTER TABLE team ENABLE ROW LEVEL SECURITY;
ALTER TABLE testimonials ENABLE ROW LEVEL SECURITY;
ALTER TABLE gallery ENABLE ROW LEVEL SECURITY;
ALTER TABLE contacts ENABLE ROW LEVEL SECURITY;

-- CREATE POLICIES (Example: Allow anyone to read, only authenticated to write)
CREATE POLICY "Public read access for services" ON services FOR SELECT USING (true);
CREATE POLICY "Admin write access for services" ON services FOR ALL USING (auth.role() = 'authenticated');

CREATE POLICY "Public read access for products" ON products FOR SELECT USING (true);
CREATE POLICY "Admin write access for products" ON products FOR ALL USING (auth.role() = 'authenticated');

CREATE POLICY "Public read access for team" ON team FOR SELECT USING (true);
CREATE POLICY "Admin write access for team" ON team FOR ALL USING (auth.role() = 'authenticated');

CREATE POLICY "Public read access for testimonials" ON testimonials FOR SELECT USING (true);
CREATE POLICY "Admin write access for testimonials" ON testimonials FOR ALL USING (auth.role() = 'authenticated');

CREATE POLICY "Public read access for gallery" ON gallery FOR SELECT USING (true);
CREATE POLICY "Admin write access for gallery" ON gallery FOR ALL USING (auth.role() = 'authenticated');

CREATE POLICY "Public insert access for contacts" ON contacts FOR INSERT WITH CHECK (true);
CREATE POLICY "Admin access for contacts" ON contacts FOR ALL USING (auth.role() = 'authenticated');
