
import { createClient } from '@supabase/supabase-js';

const supabaseUrl = 'https://wbnrfhmbwtldxhnauggx.supabase.co';
const supabaseAnonKey = 'sb_publishable_y_uGR1s1Jo2DfNrPF6saxw_fLagYUVV';

export const supabase = createClient(supabaseUrl, supabaseAnonKey);

/**
 * Helper to upload image to Supabase Storage
 * @param file The file object from input
 * @param bucket Bucket name (default is 'assets')
 */
export async function uploadImage(file: File, bucket: string = 'assets'): Promise<string | null> {
  try {
    const fileExt = file.name.split('.').pop();
    const fileName = `${Date.now()}-${Math.random().toString(36).substring(2)}.${fileExt}`;
    const filePath = `${fileName}`;

    console.log(`Attempting to upload ${fileName} to bucket: ${bucket}`);

    const { data: uploadData, error: uploadError } = await supabase.storage
      .from(bucket)
      .upload(filePath, file, {
        cacheControl: '3600',
        upsert: false,
        contentType: file.type // Explicitly set content type
      });

    if (uploadError) {
      console.error('Supabase Storage Upload Error Details:', uploadError);
      return null;
    }

    console.log('Upload successful:', uploadData);

    const { data: urlData } = supabase.storage.from(bucket).getPublicUrl(filePath);
    return urlData.publicUrl;
  } catch (err) {
    console.error('Unexpected error during image upload:', err);
    return null;
  }
}
